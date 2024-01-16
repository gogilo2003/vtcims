export interface iLink {
    url: String,
    label: String,
    active: String,
}
export interface iStudent {
    id: Number | null,
    photo: string,
    photo_url: string,
    surname: string,
    first_name: string,
    middle_name: string,
    phone: string,
    email: string,
    box_no: string,
    post_code: string,
    town: string,
    physical_address: string,
    date_of_birth: Date,
    birth_cert_no: string,
    idno: Number | null,
    gender: string,
    date_of_admission: Date | null,
    intake_id: Number | null,
    program_id: Number | null,
    sponsor_id: Number | null,
    student_role_id: Number | null,
    name: string,
    intake_name: string,
    program_name: string,
    sponsor_name: string,
    course_name: string,
    student_role_name: string,
    admission_no: string,
    status: string,
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
    id: Number,
    name: String
}

export interface iPhoto {
    id: Number | null,
    url: string,
    photo: string
}
