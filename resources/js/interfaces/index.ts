export interface iLink {
    url: String,
    label: String,
    active: String,
}
export interface iStudent {
    id: number | null,
    photo?: string,
    photo_url?: string | "",
    surname: string | "",
    first_name: string | "",
    middle_name?: string | "",
    phone: string,
    email?: string | "",
    box_no?: string | "",
    post_code?: string | "",
    town?: string | "",
    physical_address: string | "",
    date_of_birth: Date,
    birth_cert_no?: number | null,
    idno?: number | null,
    gender: boolean,
    plwd: boolean,
    plwd_details?: string | "",
    date_of_admission: Date | null,
    intake: { id: number, name: string } | number | null,
    program: { id: number, name: string, description?: string } | number | null,
    sponsor: {
        id: number,
        name: string,
        contact_person?: string
        email?: string
        phone?: string
        box_no?: string
        post_code?: string
        town?: string
        address?: string
    } | number | null,
    role: { id: number, name: string, description?: string } | number | null,
    admission_no?: string | "",
    status: { id: number, name: string } | string | number | null | "",
}
export interface iStudents {
    data: Array<iStudent>
    current_page: number,
    first_page_url: string | null,
    from: number,
    last_page: number,
    last_page_url: string | null,
    links: Array<iLink>,
    next_page_url: string | null,
    path: string | null,
    per_page: number,
    prev_page_url: string | null,
    to: number,
    total: number,
}
export interface iItem {
    id: number,
    name: string
}

export interface iPhoto {
    id: number | null,
    url?: string | null,
    photo?: string | null
}

export interface iMenu {
    name: string
    icon: string
    caption: string
    items: iMenuItem[] | null
}

export interface iMenuItem {
    name: string,
    caption: string,
}

export interface iCourse {
    id: number
    code: string
    name: string
    staff: Array<{
        id: number,
        name: string,
    }>
}
export interface iSubject {
    id: number,
    code: string,
    name: string,
    courses: Array<{
        id: number,
        code: string,
        name: string
    }>
}

export interface iInstructor {
    id: number,
    name: string
}

export interface iStaff {
    id: number | null,
    idno?: number | null,
    pfno?: number | null,
    manno?: number | null,
    photo?: string | "",
    photo_url?: string | "",
    surname?: string | "",
    first_name?: string | "",
    middle_name?: string | "",
    box_no?: string | "",
    post_code?: string | "",
    town?: string | "",
    email?: string | "",
    phone?: string | "",
    employer?: { id: number, name: string } | number | "",
    gender?: boolean | null,
    plwd?: boolean | null,
    role?: { id: number, name: string } | number | null,
    job_group?: { id: number, name: string } | number | null,
    designation?: { id: number, name: string } | number | null,
    status?: { id: number, name: string } | number | null,
    teach?: boolean | null,
    user?: { id: number, name: string, email: string } | number | null,
}
export interface iDepartment {
    id: number,
    code: string,
    name: string,
    hod: iInstructor
}

export interface iIntake {
    id: number
    start_date: string
    end_date: string
    name: string
    staff: iInstructor
    course: iCourse,
}

export interface iProgram {
    id: number
    name: string
    description: string
}

export interface iSponsor {
    id: number
    name: string
    contact_person: string
    email: string
    phone: string
    box_no: string
    post_code: string
    town: string
    address: string
}

export interface iAllocation {
    id: number
    term: {
        id: number
        name: string
        year: number
        start_date: string
        end_date: string
        year_name: string
    }
    instructor: {
        id: number,
        name: string
    }
    subject: {
        id: number,
        code: string,
        name: string
    }
    intakes: Array<{
        id: number
        name: string
    }> | []
    lessons: Array<{
        id: number
        title: string
        day: string
        start_at: string
        end_at: string
    }> | []
}
export interface iAllocations {
    current_page: string
    data: iAllocation[]
    first_page_url: string
    from: number
    last_page: number
    last_page_url: string
    links: iLink[]
    next_page_url: string
    path: string
    per_page: number
    prev_page_url: string
    to: number
    total: number
}

export interface iTerm {
    id: number
    name: string
    year: string
    year_name: string
    start_date: string
    end_date: string
}
export interface iAttendance {
    id: number
    term: iTerm
    instructor: iInstructor
    subject: iSubject
    intakes: iIntake[]
}

export interface iLesson {
    id: number
    title: string
    day: string
    start_at: any
    end_at: any
}

export interface iAllocationLesson {
    id: number,
    title: string,
    term: string,
    day: number | string,
    instructor: string,
    subject: string,
    intakes: [],
    students: [],
}

export interface iBogMember {
    id: number | null
    photo?: string | "" | null
    photo_url?: string | ""
    idno?: number | null
    gender: string | null
    plwd: boolean | false
    surname: string
    first_name: string
    middle_name?: string | ""
    phone?: string | ""
    email?: string | ""
    box_no?: string | ""
    post_code?: string | ""
    town?: string | ""
    position: { id: number, name: string | "" } | null | number
    active: boolean
    term_start_at: Date | null
    term_end_at: Date | null
    term_count?: number | 0
}

export interface iBogMembers {
    data: Array<iBogMember>
    current_page: number,
    first_page_url: string | null,
    from: number,
    last_page: number,
    last_page_url: string | null,
    links: Array<iLink>,
    next_page_url: string | null,
    path: string | null,
    per_page: number,
    prev_page_url: string | null,
    to: number,
    total: number,
}

export interface iStaffMembers {
    data: Array<iStaff>
    current_page: number,
    first_page_url: string | null,
    from: number,
    last_page: number,
    last_page_url: string | null,
    links: Array<iLink>,
    next_page_url: string | null,
    path: string | null,
    per_page: number,
    prev_page_url: string | null,
    to: number,
    total: number,
}

export interface iBogPosition {
    id?: number | null
    name?: string | ""
    members?: number | Array<iBogMember> | null
}
export interface iBogPositions {
    data: Array<iBogPosition>
    current_page: number,
    first_page_url: string | null,
    from: number,
    last_page: number,
    last_page_url: string | null,
    links: Array<iLink>,
    next_page_url: string | null,
    path: string | null,
    per_page: number,
    prev_page_url: string | null,
    to: number,
    total: number,
}

export interface iNotification {
    success?: string | null
    danger?: string | null
    info?: string | null
    warning?: string | null
}

export interface iStaffRole {
    id?: number | null
    name?: string | ""
    members?: number | Array<iStaff> | null
}
export interface iStaffRoles {
    data: Array<iStaffRole>
    current_page: number,
    first_page_url: string | null,
    from: number,
    last_page: number,
    last_page_url: string | null,
    links: Array<iLink>,
    next_page_url: string | null,
    path: string | null,
    per_page: number,
    prev_page_url: string | null,
    to: number,
    total: number,
}
export interface iStaffStatus {
    id?: number | null
    name?: string | ""
    description?: string | ""
    members?: number | Array<iStaff> | null
}
export interface iStaffStatuses {
    data: Array<iStaffStatus>
    current_page: number,
    first_page_url: string | null,
    from: number,
    last_page: number,
    last_page_url: string | null,
    links: Array<iLink>,
    next_page_url: string | null,
    path: string | null,
    per_page: number,
    prev_page_url: string | null,
    to: number,
    total: number,
}
export interface iEmployer {
    id?: number | null
    name?: string | ""
    members?: number | Array<iStaff> | null
}
export interface iEmployers {
    data: Array<iEmployer>
    current_page: number,
    first_page_url: string | null,
    from: number,
    last_page: number,
    last_page_url: string | null,
    links: Array<iLink>,
    next_page_url: string | null,
    path: string | null,
    per_page: number,
    prev_page_url: string | null,
    to: number,
    total: number,
}

export interface iJobGroup {
    id?: number | null
    name?: string | ""
    members?: number | Array<iStaff> | null
}
export interface iJobGroups {
    data: Array<iJobGroup>
    current_page: number,
    first_page_url: string | null,
    from: number,
    last_page: number,
    last_page_url: string | null,
    links: Array<iLink>,
    next_page_url: string | null,
    path: string | null,
    per_page: number,
    prev_page_url: string | null,
    to: number,
    total: number,
}

export interface iDesignation {
    id?: number | null
    name?: string | ""
    members?: number | Array<iStaff> | null
}
export interface iDesignations {
    data: Array<iDesignation>
    current_page: number,
    first_page_url: string | null,
    from: number,
    last_page: number,
    last_page_url: string | null,
    links: Array<iLink>,
    next_page_url: string | null,
    path: string | null,
    per_page: number,
    prev_page_url: string | null,
    to: number,
    total: number,
}
