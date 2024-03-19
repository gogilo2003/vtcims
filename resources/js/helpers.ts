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
