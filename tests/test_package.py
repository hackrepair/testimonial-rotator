from pathlib import Path
from tempfile import TemporaryDirectory
import hashlib
import importlib.util
import unittest
import zipfile
ROOT = Path(__file__).resolve().parents[1]
spec = importlib.util.spec_from_file_location("builder", ROOT / "scripts/build-release.py")
builder = importlib.util.module_from_spec(spec)
spec.loader.exec_module(builder)

class PackagingTests(unittest.TestCase):
    def test_deterministic_runtime_only_archive(self):
        with TemporaryDirectory() as temp:
            first = builder.build(Path(temp) / "first.zip")
            second = builder.build(Path(temp) / "second.zip")
            self.assertEqual(first.read_bytes(), second.read_bytes())
            digest = hashlib.sha256(first.read_bytes()).hexdigest()
            self.assertTrue(first.with_suffix(".zip.sha256").read_text().startswith(digest))
            with zipfile.ZipFile(first) as archive:
                for name in archive.namelist():
                    parts = Path(name).parts
                    self.assertEqual(parts[0], "testimonial-rotator")
                    self.assertNotIn(parts[1], ("tests", "scripts", "docs", ".github", "dist", "PROJECT.md", "README.md"))
                    self.assertEqual(archive.read(name), (ROOT / Path(*parts[1:])).read_bytes())
                    self.assertEqual((archive.getinfo(name).external_attr >> 16) & 0o777, 0o644)
                self.assertIn("testimonial-rotator/assets/brand/thrg-heart-256.png", archive.namelist())
                self.assertIn("testimonial-rotator/includes/class-thrg-tr-details.php", archive.namelist())
