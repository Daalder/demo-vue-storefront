const getImageSrc = (media, size) => {
    let image;
    media.versions.forEach(version => {
        if (version.metadata.size === size) {
            image = version.src;
        }
    });

    return image || media.src;
}

const getProductImageSrc = (product, index = 0, size = 'preview') => {
    if (!product.hasOwnProperty('media'))
        return null;

    let images = collect(product.media).filter((mediaObj) => mediaObj.type.code === "image");

    if (images.length <= index)
        return null;

    let image = images.get(index);

    if (!image) {
        return null;
    }

    return getImageSrc(image, size);
}

module.exports = {
    getImageSrc,
    getProductImageSrc
}
