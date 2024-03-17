import { iMenu } from "./interfaces"

export const links: iMenu[] = [
    {
        name: 'dashboard',
        icon: 'dashboard',
        caption: 'Dashboard',
        items: null
    },
    {
        name: '',
        icon: 'people',
        caption: 'Students',
        items: [
            {
                name: 'students',
                caption: 'Students'
            },
            {
                name: 'attendances',
                caption: 'Attendances'
            }
        ]
    },
    {
        name: '',
        icon: 'settings',
        caption: 'Setup',
        items: [
            {
                name: 'programs',
                caption: 'Programs',
            },
            {
                name: 'sponsors',
                caption: 'Sponsors',
            },
            {
                name: 'departments',
                caption: 'Departments',
            },
            {
                name: 'courses',
                caption: 'Courses',
            },
            {
                name: 'intakes',
                caption: 'Intakes',
            },
            {
                name: 'subjects',
                caption: 'Subjects',
            },
            {
                name: 'allocations',
                caption: 'Allocations',
            },
            {
                name: 'lessons',
                caption: 'Lessons',
            },
        ]
    },
]
