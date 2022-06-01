import os
import sys
from argparse import ArgumentParser

from PIL import Image


class ImageFile:
    def __init__(self, filename: str, size: tuple[int, int]):
        self.filename = filename
        self.width = size[0]
        self.height = size[1]


def list_images(images: list[ImageFile]):
    """Displays a list of images with sizes and filenames"""
    print(*[f"{f.width:<4} x {f.height:<4} : {f.filename}" for f in images], sep="\n")


def list_small_images(
    images: list[ImageFile], *, min_width: int, min_height: int
) -> int:
    """Lists images that are smaller than min_width x min_height and returns the number of images"""
    # sort by number of pixels
    images.sort(key=lambda x: x.width * x.height)
    # list images if the width or height is less than min_width or min_height
    filtered = [f for f in images if f.width < min_width or f.height < min_height]
    if len(filtered) > 0:
        print(f"\n{len(filtered)} images are smaller than {min_width} x {min_height}")
        list_images(filtered)
    return len(filtered)


def list_narrow_images(images: list[ImageFile], *, max_ratio: float) -> int:
    """Lists images having width/height less than max_ratio and returns the number of images"""
    # sort by width to height ratio
    images.sort(key=lambda x: x.width / x.height)
    # list images where the width/height ratio is less than max_ratio
    filtered = [f for f in images if f.width / f.height < max_ratio]
    if len(filtered) > 0:
        print(f"\n{len(filtered)} images have width/height less than {max_ratio}")
        list_images(filtered)
    return len(filtered)


def filter_wallpapers(dir: str, min_width: int, min_height: int, max_ratio: float):
    """Filters wallpapers in the current directory."""
    dirpath = os.path.abspath(dir)
    images = [
        ImageFile(f, Image.open(os.path.join(dirpath, f)).size)
        for f in os.listdir(dirpath)
    ]
    small = list_small_images(images, min_width=min_width, min_height=min_height)
    narrow = list_narrow_images(images, max_ratio=max_ratio)
    if small + narrow > 0:
        sys.exit(f"Found {small} small images and {narrow} narrow images.")


if __name__ == "__main__":
    # read image directory path, min_width, min_height, max_ratio from passed arguments
    parser = ArgumentParser(description="Filter wallpapers in the current directory.")
    parser.add_argument(
        "--dir",
        "-d",
        type=str,
        default=".",
        help="Directory to filter wallpapers in. Defaults to current directory.",
    )
    parser.add_argument(
        "--min_width",
        type=int,
        default=1920,
        help="minimum width of images to keep (default: 1920)",
    )
    parser.add_argument(
        "--min_height",
        type=int,
        default=1080,
        help="minimum height of images to keep (default: 1080)",
    )
    parser.add_argument(
        "--max_ratio",
        type=float,
        default=1.5,
        help="maximum width/height ratio of images to keep (default: 1.5)",
    )
    args = parser.parse_args()
    filter_wallpapers(args.dir, args.min_width, args.min_height, args.max_ratio)
