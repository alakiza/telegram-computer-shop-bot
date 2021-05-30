
export function arrayToDictionaryByID(array) {
    let res = []
    array.forEach((item) => {
        res[item.id] = item
    })
    return res
}