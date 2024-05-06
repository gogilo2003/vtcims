export interface iLink {
    url: string,
    label: string,
    active: string,
}
export interface iStudent {
    id: number | null,
    photo?: string | null,
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
    date_of_birth: Date | null,
    birth_cert_no?: number | null,
    idno?: number | null,
    gender: boolean,
    plwd: boolean,
    plwd_details?: string | "",
    date_of_admission: Date,
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
    fees?: { charged: number, paid: number, balance: number }
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
    id: number | null
    code: string
    name: string
    duration?: number | null
    internship_duration?: number | null
    department?: Array<{
        id: number,
        name: string,
    }> | null | number
    staff?: Array<{
        id: number,
        name: string,
    }> | null | number
}
export interface iCourses {
    data: Array<iCourse>
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
export interface iSubject {
    id: number | null,
    code: string,
    name: string,
    courses: Array<{
        id: number,
        code: string,
        name: string
    }> | number[]
}
export interface iSubjects {
    data: Array<iSubject>
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
    id: number | null,
    code: string | "",
    name: string | "",
    hod: iInstructor | number | null
}
export interface iDepartments {
    data: Array<iDepartment>
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

export interface iIntake {
    id: number | null
    start_date: Date | null
    end_date: Date | null
    name: string
    instructor: iInstructor | number | null
    course: iCourse | number | null,
}

export interface iIntakes {
    data: Array<iIntake>
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

export interface iProgram {
    id: number | null
    name: string | ""
    description?: string
}
export interface iPrograms {
    data: Array<iProgram>
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

export interface iSponsor {
    id: number | null
    name: string
    contact_person: string
    email: string
    phone: string
    box_no: string
    post_code: string
    town: string
    address: string
}

export interface iSponsors {
    data: Array<iSponsor>
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
    id: number | null
    name: string | null
    year: number | null | string
    start_date: Date | null
    end_date: Date | null
    auto_allocate?: number
}
export interface iTerms {
    current_page: string
    data: iTerm[]
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
export interface iLessons {
    data: Array<iLesson>
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

export interface iExamination {
    id: number | null
    title: string | ""
    intakes: iItem[] | null
    tests: { id: number, title: string }[]
    students: {
        id: number,
        name?: string,
        admission_no?: string,
        results: {
            id: number | null
            test_id: number
            score: number
            max: number
        }[]
    }[]

}
export interface iExaminations {
    data: Array<iExamination>
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

export interface iStudentRole {
    id: number | null
    name: string | null
    description?: string | null
    students?: iStudent[] | null
}
export interface iStudentRoles {
    data: Array<iStudentRole>
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

export interface iMark {
    code: string
    subject: string
    score: number
    grade: number
    remark: string
    average: number
    min: number
    max: number
}

export interface iTranscript {
    admission_no: number,
    name: string,
    intake: string,
    course: string,
    marks: iMark[],
    total: number
    average: number
    grade: number
    remark: string
}

export interface iTransaction {
    id: number | null
    particulars: string | ""
    fee: { id: number, name: string } | number | null
    type: { id: number, name: string } | number | null
    mode: string | ""
    amount: number | null
    students?: iStudent[]
}

export interface iFee {
    id: number | null
    term: { id: number, name: string } | number | null
    course: { id: number, name: string } | number | null
    amount: number | null
    transactions?: iTransaction[] | null
}
export interface iFees {
    data: Array<iFee>
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

export interface iFeeTransaction {
    id: number | null
    student: { id: number, admission_no: string, name: string } | number | null
    fee: { id: number, name: string } | number | null
    mode: { id: number, name: string } | number | null
    amount: number | null
    particulars: string
    date: Date
}
export interface iFeeTransactions {
    data: Array<iFeeTransaction>
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
