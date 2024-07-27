export const getReordered = (event, questions) => {
    const movedQuestion = questions.find((question, index) => index === event.oldIndex);
    const remainingQuestions = questions.filter((question, index) => index !== event.oldIndex);
    const reorderedQuestions = [];

    remainingQuestions.forEach((question, index) => {
        if (index === event.newIndex) {
            reorderedQuestions.push(movedQuestion);
            reorderedQuestions.push(question);
        } else {
            reorderedQuestions.push(question);
        }
    });
    if (event.newIndex === remainingQuestions.length) reorderedQuestions.push(movedQuestion);

    return reorderedQuestions;
}
