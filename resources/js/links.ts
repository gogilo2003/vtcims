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
        icon: 'gear',
        caption: 'Administrator',
        items: [
            {
                name: 'admin-roles',
                caption: 'Roles'
            }
        ]
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
            },
            {
                name: 'students-roles',
                caption: 'Roles'
            }
        ]
    },
    {
        name: '',
        icon: 'teacher',
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
        icon: 'board_member',
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
            {
                name: "examinations-transcripts",
                caption: "Transcripts"
            },
        ]
    },
    {
        name: '',
        icon: 'money',
        caption: 'Accounts',
        items: [
            {
                name: 'accounts',
                caption: 'Dashboard',
            },
            {
                name: 'accounts-transactions',
                caption: 'Fee Transactions',
            },
            {
                name: 'accounts-fees',
                caption: 'Fees Setup',
            },
            {
                name: 'accounts-invoices',
                caption: 'Invoices',
            },
        ]
    },
    {
        name: '',
        icon: 'settings',
        caption: 'Setup',
        items: [
            {
                name: 'terms',
                caption: 'Terms',
            },
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
