#!/usr/bin/env python3
"""Build a deterministic, verified staging ZIP from an explicit runtime-file allowlist."""
from pathlib import Path
import argparse, hashlib, re, zipfile
ROOT = Path(__file__).resolve().parents[1]
RUNTIME_DIRS = {"admin", "assets", "includes", "js", "languages", "templates"}
RUNTIME_FILES = {"readme.txt", "LICENSE", "testimonial-rotator-style.css"}

def build(output=None):
    main = (ROOT / "testimonial-rotator.php").read_text()
    version = re.search(r"^Version:\s*(\S+)", main, re.M).group(1)
    stable = re.search(r"^Stable tag:\s*(\S+)", (ROOT / "readme.txt").read_text(), re.M).group(1)
    constant = re.search(r"define\( 'THRG_TR_VERSION', '([^']+)'", main).group(1)
    if not version == stable == constant:
        raise ValueError("Header, readme and details version must agree")
    output = Path(output) if output else ROOT / "dist" / ("testimonial-rotator-reborn." + version + "-development.zip")
    output.parent.mkdir(parents=True, exist_ok=True)
    files = []
    for path in sorted(ROOT.rglob("*")):
        rel = path.relative_to(ROOT)
        if path.is_symlink():
            if rel.parts[0] in RUNTIME_DIRS: raise ValueError("Runtime symlink: " + str(rel))
            continue
        if not path.is_file() or any(part.startswith(".") for part in rel.parts): continue
        if rel.parts[0] in RUNTIME_DIRS or len(rel.parts) == 1 and (path.suffix == ".php" or path.name in RUNTIME_FILES):
            files.append((path, "testimonial-rotator/" + rel.as_posix()))
    with zipfile.ZipFile(output, "w", zipfile.ZIP_DEFLATED) as archive:
        for path, name in files:
            info = zipfile.ZipInfo(name, (2026, 11, 6, 0, 0, 0))
            info.create_system = 3
            info.external_attr = 0o100644 << 16
            info.compress_type = zipfile.ZIP_DEFLATED
            archive.writestr(info, path.read_bytes())
    with zipfile.ZipFile(output) as archive:
        assert archive.testzip() is None
        assert "testimonial-rotator/testimonial-rotator.php" in archive.namelist()
        assert "testimonial-rotator/LICENSE" in archive.namelist()
        for path, name in files: assert archive.read(name) == path.read_bytes()
    digest = hashlib.sha256(output.read_bytes()).hexdigest()
    output.with_suffix(output.suffix + ".sha256").write_text(digest + "  " + output.name + "\n")
    return output

if __name__ == "__main__":
    parser = argparse.ArgumentParser()
    parser.add_argument("--output", type=Path)
    print(build(parser.parse_args().output))
