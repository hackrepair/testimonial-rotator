#!/usr/bin/env python3
"""Install the built ZIP in a dedicated disposable WordPress/PHP 8.3 lab."""
from pathlib import Path
from tempfile import TemporaryDirectory
import http.cookiejar
import json
import os
import re
import subprocess
import time
import urllib.parse
import urllib.request

ROOT = Path(__file__).resolve().parents[1]

def run(*args, capture=False):
    result = subprocess.run(args, check=True, text=True, stdout=subprocess.PIPE if capture else None)
    return result.stdout if capture else ''

run('python3', str(ROOT / 'scripts/build-release.py'))
with TemporaryDirectory(prefix='reborn-lab-') as temp:
    temp = Path(temp)
    project = 'reborn-ci-' + str(os.getpid())
    compose = temp / 'compose.yml'
    compose.write_text('''services:
  db:
    image: mariadb:10.11
    environment:
      MARIADB_DATABASE: wordpress
      MARIADB_USER: wordpress
      MARIADB_PASSWORD: fixture-only
      MARIADB_ROOT_PASSWORD: fixture-root-only
    healthcheck:
      test: [CMD, healthcheck.sh, --connect, --innodb_initialized]
      interval: 2s
      timeout: 5s
      retries: 45
  wordpress:
    image: wordpress:php8.3-apache
    ports:
      - "127.0.0.1::80"
    environment:
      WORDPRESS_DB_HOST: db
      WORDPRESS_DB_NAME: wordpress
      WORDPRESS_DB_USER: wordpress
      WORDPRESS_DB_PASSWORD: fixture-only
      WORDPRESS_DEBUG: '1'
      WORDPRESS_CONFIG_EXTRA: |
        define('WP_DEBUG_DISPLAY', false);
        define('WP_DEBUG_LOG', true);
        define('DISABLE_WP_CRON', true);
    depends_on:
      db:
        condition: service_healthy
''')
    cmd = ['docker', 'compose', '-p', project, '-f', str(compose)]
    def dc(*args, capture=False): return run(*(cmd + list(args)), capture=capture)
    def wp(*args): return dc('exec', '-T', '-u', 'www-data', 'wordpress', 'php', '/tmp/wp-cli.phar', *args, capture=True)
    try:
        dc('up', '-d', '--wait', '--wait-timeout', '120')
        address = dc('port', 'wordpress', '80', capture=True).strip()
        base = 'http://' + address
        phar = temp / 'wp-cli.phar'
        run('curl', '--fail', '--location', '--retry', '2', '--max-time', '90', '--output', str(phar), 'https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar')
        dc('cp', str(phar), 'wordpress:/tmp/wp-cli.phar')
        for attempt in range(30):
            try:
                urllib.request.urlopen(base, timeout=5).read()
                break
            except Exception:
                if attempt == 29: raise
                time.sleep(1)
        print(wp('core', 'install', '--url=' + base, '--title=Reborn Lab', '--admin_user=fixture', '--admin_password=fixture-admin-only', '--admin_email=fixture@example.test', '--skip-email'))
        archive = ROOT / 'dist/testimonial-rotator-reborn.4.0-development.zip'
        dc('cp', str(archive), 'wordpress:/tmp/reborn.zip')
        print(wp('plugin', 'install', '/tmp/reborn.zip', '--activate'))
        print('WordPress ' + wp('core', 'version').strip())
        print(dc('exec', '-T', 'wordpress', 'php', '-r', 'echo "PHP ", PHP_VERSION, "\n";', capture=True))
        dc('cp', str(ROOT / 'tests/integration.php'), 'wordpress:/tmp/integration.php')
        print(wp('eval-file', '/tmp/integration.php'))
        opener = urllib.request.build_opener(urllib.request.HTTPCookieProcessor(http.cookiejar.CookieJar()))
        opener.open(base + '/wp-login.php').read()
        opener.open(base + '/wp-login.php', urllib.parse.urlencode({'log': 'fixture', 'pwd': 'fixture-admin-only', 'redirect_to': base + '/wp-admin/', 'testcookie': '1'}).encode()).read()
        html = opener.open(base + '/wp-admin/plugins.php').read().decode()
        row = re.search(r'<tr[^>]*data-plugin="testimonial-rotator/testimonial-rotator.php".*?</tr>', html, re.S).group(0)
        assert 'Hal Gatewood (abandoned website)' in row
        assert 'Visit plugin site' not in row
        assert row.count('>View details</a>') == 1
        detail = opener.open(base + '/wp-admin/plugin-install.php?tab=plugin-information&plugin=testimonial-rotator&TB_iframe=true').read().decode()
        for section in ['description', 'installation', 'history', 'faq', 'changelog', 'support']:
            assert re.search(r"id=['\"]section-" + section + r"['\"]", detail), section
        assert 'Future updates are unlikely.' in detail
        assert 'halgatewood.com' not in detail
        print('PASS authenticated Plugins row and all native details tabs')
        # A real plugin install must not emit PHP warnings or errors in this fixture run.
        log = dc('exec', '-T', 'wordpress', 'sh', '-c', 'if [ -f /var/www/html/wp-content/debug.log ]; then cat /var/www/html/wp-content/debug.log; fi', capture=True)
        assert not log.strip(), 'Unexpected PHP debug log:\n' + log
        print('PASS clean PHP debug log')
    finally:
        dc('down', '--volumes', '--remove-orphans')
