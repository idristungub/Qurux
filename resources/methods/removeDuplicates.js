export const removeDuplicates = function (array) {
    return array.value.filter((value, index) => array.indexOf(value) === index)
}
