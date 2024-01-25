export function generateIntakeName(code: string, date: any): string {
    let months = ['JAN', 'FEB', 'MAR', 'APR', 'MAY']
    let dt = new Date(date)
    code = code || ''
    return `${code}/${dt.getFullYear()}/${months[dt.getMonth()]}`
}
