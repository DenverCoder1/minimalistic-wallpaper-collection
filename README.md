# Minimalistic Wallpaper Collection

[![images](https://custom-icon-badges.demolab.com/github/directory-file-count/DenverCoder1/Minimalistic-Wallpaper-Collection/images?label=images&logo=image)](https://github.com/DenverCoder1/Minimalistic-Wallpaper-Collection/tree/main/images)
[![license](https://custom-icon-badges.demolab.com/github/license/denvercoder1/custom-icon-badges?logo=file-badge&logoColor=white&label=code+license&color=success)](https://github.com/DenverCoder1/Minimalistic-Wallpaper-Collection/blob/main/LICENSE)
[![discord](https://custom-icon-badges.demolab.com/discord/819650821314052106?color=7289DA&logo=comments&label=discord&logoColor=white)](https://discord.gg/fPrdqh3Zfu)

This is my collection of minimalistic, flat art, and colorful, digital nature wallpapers.

**Disclaimer:** I do not claim ownership of the wallpapers appearing in this repository. Most of these wallpapers were found on [Reddit](https://www.reddit.com/r/wallpaper/), DeviantArt, or Artstation. If you find images in this repository owned by you and are of limited use, please let me know and I will remove them.

## Wallpapers

To view a gallery of all 300+ wallpapers, [click here](https://mwc.deno.dev/)!

[![screenshot](https://user-images.githubusercontent.com/20955511/186479660-475532e8-427d-4df1-a19d-7f94090805b1.png)](https://mwc.deno.dev/)

## API

The website also includes a random wallpaper API, so you can request random wallpapers from the collection using a web request.

To use the API, simply make a request to:

```md
https://mwc.deno.dev/?random
```

To get multiple random images at once, it is recommended to change the URL slightly to avoid caching. For example,

```md
https://mwc.deno.dev/?random=1
https://mwc.deno.dev/?random=2
https://mwc.deno.dev/?random=3
```

This API can be used for setting daily wallpapers on a mobile device by combining it with an app such as IFTTT.

> **Note**
> By default, the API will fetch the image from GitHub and return it as content if it is smaller than 4.5MB.
> To force the API to redirect to the image on GitHub instead, add `&redirect=1` to the end of the URL.

## Contributing

All images appearing in the images directory contain the author (sometimes this is the original artist, for others, this is a Reddit user who shared the image or a website the image was found on in cases where the original artist is unknown) and the title or description of the wallpaper.

* If the artist is not properly attributed, feel free to open an issue or pull request for renaming the files to give the author the correct attribution
* New wallpapers may be suggested by opening a pull request, but as this is a personal wallpaper collection, it is up to my discretion whether or not the wallpaper will be accepted
* If you are able to find or create higher resoluion versions of the existing wallpapers, feel free to do so and open a PR. Images should be at least 1920x1080, the preferrable size is 3840x2160, larger than this is not necessary. Note that a resized image is not considered higher resolution, however, an image upscaled using an AI image restoration model can be used if the output is not pixelated or blurry.
* Issues and pull requests regarding improvements to the website or API are welcome

