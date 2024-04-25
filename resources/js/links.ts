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
        icon: 'people',
        caption: 'Staff',
        items: [
            {
                name: "staff-members",
                caption: "Members"
            },
            {
                name: "staff-roles",
                caption: "Roles"
            },
            {
                name: "staff-status",
                caption: "Statuses"
            },
            {
                name: "staff-employers",
                caption: "Employers"
            },
            {
                name: "staff-job_groups",
                caption: "Job Groups"
            },
            {
                name: "staff-designations",
                caption: "Designations"
            },
        ]
    },
    {
        name: '',
        icon: 'people',
        caption: 'BOG',
        items: [
            {
                name: "bog-members",
                caption: "Members"
            },
            {
                name: "bog-positions",
                caption: "Positions"
            },
        ]
    },
    {
        name: '',
        icon: 'certificate',
        caption: 'Examinations',
        items: [
            {
                name: "examinations",
                caption: "Examinations"
            },
            // {
            //     name: "bog-positions",
            //     caption: "Positions"
            // },
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
                name: 'lessons',
                caption: 'Lessons',
            },
            {
                name: 'allocations',
                caption: 'Allocations',
            },
        ]
    },
]
