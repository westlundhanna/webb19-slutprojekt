function onlyOne(checkbox) {
    let checkboxes = document.getElementsByName('rate')
    checkboxes.forEach((item) => {
        if (item !== checkbox) item.checked = false
    })
}