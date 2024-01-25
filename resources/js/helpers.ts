export function generateIntakeName(code: string, date: any): string {
    let months = ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC']
    let dt = new Date(date)
    code = code || ''
    return code && date ? `${code}/${dt.getFullYear()}/${months[dt.getMonth()]}` : ''
}
