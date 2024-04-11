export function generateIntakeName(code: string, date: any): string {
    let months = ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC']
    let dt = new Date(date)
    code = code || ''
    return code && date ? `${code}/${dt.getFullYear()}/${months[dt.getMonth()]}` : ''
}

export function getShortDayName(fullDayName: string) {
    const date = new Date();
    const dayIndex = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'].indexOf(fullDayName);
    if (dayIndex !== -1) {
        date.setDate(date.getDate() + dayIndex - date.getDay());
        return date.toLocaleDateString('en-US', { weekday: 'short' });
    }
    return null; // Handle invalid day names
}
/**
 *
 * @param lessonDay
 * @returns
 */
// Function to calculate the suggested date
export const calculateSuggestedDate = (lessonDay: number): string => {

    const currentDate = new Date();
    // const currentDayOfWeek = currentDate.getDay(); // 0 (Sunday) to 6 (Saturday)

    const currentDay = new Date(currentDate);

    // Find the next scheduled day for the lesson
    for (let i = 0; i < 7; i++) {
        currentDay.setDate(currentDay.getDate() + 1); // Move to the next day
        const dayOfWeek = currentDay.getDay();
        if (lessonDay == dayOfWeek) {
            // Calculate the date based on the current day and the scheduled day
            const daysDifference = i + 1; // Add 1 to account for the current day
            const suggestedDate = new Date(currentDate);
            suggestedDate.setDate(suggestedDate.getDate() + daysDifference);
            return suggestedDate.toDateString();
        }
    }

    return null; // No lesson scheduled for today
};
