import os
from argparse import ArgumentParser
from typing import List

import tinify
from dotenv import load_dotenv
from tqdm import tqdm


def filter_files(files: List[str], extensions: List[str]):
    """Filters files by extension"""
    return [f for f in files if f.split(".")[-1] in extensions]


def tinify_files(dirpath: str, files: List[str]):
    """Tinifies files in the a given directory"""
    for filename in tqdm(files):
        with open(f"{dirpath}/{filename}", "rb") as source:
            tinify.from_buffer(source.read()).to_file(f"{dirpath}/{filename}")


if __name__ == "__main__":
    load_dotenv()

    parser = ArgumentParser(description="Use TinyPNG to optimize file sizes.")
    parser.add_argument(
        "--dir",
        "-d",
        type=str,
        default=".",
        help="Directory to optimize wallpapers in. Defaults to current directory.",
    )
    parser.add_argument(
        "--files",
        "-ff",
        type=str,
        default="",
        help="Comma-separated list of files to optimize. Defaults to all files in the directory.",
    )
    parser.add_argument(
        "--key",
        "-k",
        type=str,
        default=os.getenv("TINIFY_API_KEY"),
        help="TinyPNG API key. Defaults to the TINYPNG_KEY environment variable.",
    )
    args = parser.parse_args()

    dirpath = os.path.abspath(args.dir)
    files = (
        [os.path.basename(f) for f in args.files.split(",")] if args.files else os.listdir(args.dir)
    )
    tinify.key = args.key

    files = filter_files(files, ["png", "jpg", "jpeg"])
    tinify_files(dirpath, files)
