export const arrayMatch = (userOrder:number[], correctOrder:number[]) => {
    if (userOrder.length !== correctOrder.length) {
        return false;
    }
    for (let i = 0; i < userOrder.length; i++) {
        if (userOrder[i] !== correctOrder[i]) {
            return false;
        }
    }



    return true;
}
