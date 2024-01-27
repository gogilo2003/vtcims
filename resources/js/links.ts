import { iMenu } from "./interfaces"

export const links: iMenu[] = [
    {
        name: 'dashboard',
        icon: 'dashboard',
        caption: 'Dashboard',
        items: null
    },
    {
        name: 'students',
        icon: 'people',
        caption: 'Students',
        items: null
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
        ]
    },
]
