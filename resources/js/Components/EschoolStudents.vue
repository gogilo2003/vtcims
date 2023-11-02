<template>
  <div>
    <div class="row">
      <div class="col-md-9">
        <ul class="pagination">
          <li class="paginate_button page-item first">
            <a href="#" class="page-link" v-if="page != 1" @click="page = 1">
              FIRST
            </a>
          </li>
          <li class="paginate_button page-item previous">
            <a href="#" class="page-link" v-if="page != 1" @click="page--">
              PREV
            </a>
          </li>
          <li
            :class="
              'paginate_button page-item' +
              (page == pageNumber ? ' active' : '')
            "
            v-for="pageNumber in nav(page)"
            :key="pageNumber"
          >
            <a href="#" class="page-link" @click="page = pageNumber">
              {{ pageNumber }}
            </a>
          </li>
          <li class="paginate_button page-item next">
            <a
              href="#"
              class="page-link"
              @click="page++"
              v-if="page < pages.length"
            >
              NEXT
            </a>
          </li>
          <li class="paginate_button page-item last">
            <a
              href="#"
              class="page-link"
              @click="page = pages.length"
              v-if="page < pages.length"
            >
              LAST
            </a>
          </li>
        </ul>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <label for="searchBox">Search:</label>
          <input
            type="text"
            class="form-control"
            id="searchBox"
            @keyup.enter="filterStudents"
            v-model="search"
          />
        </div>
      </div>
    </div>

    <div class="table-responsive">
      <table
        id="studentsTable"
        class="table table-striped table-hover table-bordered"
      >
        <thead class="thead-light text-uppercase">
          <tr>
            <th class="d-none d-md-table-cell"></th>
            <th class="d-none d-md-table-cell">Admission No</th>
            <th class="d-block d-md-table-cell">Name</th>
            <th class="d-none d-md-table-cell">Class</th>
            <th class="d-none d-md-table-cell">Course</th>
            <th class="d-none d-md-table-cell">Sponsor</th>
            <th class="d-none d-md-table-cell">Program</th>
            <th class="d-none d-md-table-cell"></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(student, index) in displayedStudents" :key="index">
            <td class="d-none d-md-table-cell" v-html="index + 1"></td>
            <td
              class="d-none d-md-table-cell"
              v-html="student.admission_no"
            ></td>
            <td class="d-block d-md-table-cell" v-html="student.name"></td>
            <td class="d-none d-md-table-cell" v-html="student.class"></td>
            <td class="d-none d-md-table-cell" v-html="student.course"></td>
            <td class="d-none d-md-table-cell" v-html="student.sponsor"></td>
            <td class="d-none d-md-table-cell" v-html="student.program"></td>
            <td class="d-none d-md-table-cell">
              <div class="dropdown">
                <button
                  class="btn btn-primary dropdown-toggle btn-round btn-sm"
                  type="button"
                  id="dropdownMenuButton"
                  data-toggle="dropdown"
                  aria-haspopup="true"
                  aria-expanded="false"
                >
                  <i class="material-icons">settings</i>
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                  <a
                    href="JavaScript:"
                    class="dropdown-item"
                    @click="editStudentButton(student.id)"
                  >
                    <i class="material-icons">edit</i>&nbsp;&nbsp; Edit
                  </a>
                  <a
                    :href="route('admin-eschool-students-view', student.id)"
                    class="dropdown-item"
                  >
                    <i class="material-icons">pageview</i>&nbsp;&nbsp; View
                    Details
                  </a>
                  <a
                    href="JavaScript:"
                    @click="uploadPhotoButton(student.id)"
                    class="dropdown-item uploadPhotoButton"
                  >
                    <i class="material-icons">image</i>&nbsp;&nbsp; Upload
                    Picture
                  </a>
                  <a
                    :href="route('admin-eschool-students-download', student.id)"
                    class="dropdown-item"
                  >
                    <i class="material-icons">cloud_download</i>&nbsp;&nbsp;
                    Download Details
                  </a>
                  <a
                    href="JavaScript:"
                    @click="leaveOutButton(student)"
                    class="dropdown-item"
                  >
                    <i class="material-icons">assignment</i>&nbsp;&nbsp; Leave
                    Out
                  </a>
                </div>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
<script>
import axios from "axios";
import { mapActions, mapGetters, mapMutations } from "vuex";
export default {
  data: () => {
    return {
      selected_id: null,
      table: null,
      search: "",
      page: 1,
      perPage: 10,
      pages: [],
    };
  },
  created() {
    this.fetchStudents();
    // this.filtered = this.students
  },
  mounted() {
    $(".addStudentButton").click(function () {
      $("#studentDialog").modal("show");
    });

    $("#studentDialog").on("shown.bs.modal", function () {
      $("#studentDialog #surname").focus();
    });

    $("#cancelStudedntButton").click(function () {
      this.url = route("admin-eschool-students-add-post");
      $("#studentDialog #studentDetailsForm").attr("action", this.url);

      $("#studentDialog #studentId").val(null);
      $("#studentDialog #surname").val(null);
      $("#studentDialog #first_name").val(null);
      $("#studentDialog #middle_name").val(null);
      $("#studentDialog #phone").val(null);
      $("#studentDialog #email").val(null);
      $("#studentDialog #box_no").val(null);
      $("#studentDialog #post_code").val(null);
      $("#studentDialog #town").val(null);
      $("#studentDialog #date_of_birth").val(null);
      $("#studentDialog #birth_certificate_no").val(null);
      $("#studentDialog #idno").val(null);
      $("#studentDialog #gender").selectpicker("val", null);
      $("#studentDialog #date_of_admission").val(null);
      $("#studentDialog #intake").selectpicker("val", null);
      $("#studentDialog #program").selectpicker("val", null);
      $("#studentDialog #sponsor").selectpicker("val", null);
      $("#studentDialog #student_role").selectpicker("val", null);

      $("#studentDialog").modal("hide");
    });

    $("#showAttendanceButton").click(function () {
      $("#attendanceDialog").modal("show");
    });

    $("#attendanceDetailsForm #class").selectpicker("val", null);

    $("#attendanceDetailsForm #class").change(function () {
      let intake = $(this).val();
      $.post("/api/eschool/intakes/subjects", {
        intake,
      }).then(
        function (response) {
          // console.log(response)
          $("#attendanceDetailsForm #subject").html("");
          response.forEach(function (item, index) {
            $("#attendanceDetailsForm #subject").append(
              '<option value="' + item.id + '">' + item.name + "</option>"
            );
          });

          $("#attendanceDetailsForm #subject").selectpicker("refresh");
          $("#attendanceDetailsForm #subject").selectpicker("val", null);
        },
        function (error) {
          console.log("Error");
          console.log(error.responseText);
        }
      );
    });

    $("#filterDownloadDialog").on("show.bs.modal", function () {
      $("#filterDownloadDialog #department").selectpicker("val", null);
      $("#filterDownloadDialog #course").selectpicker("val", null);
      $("#filterDownloadDialog #class").selectpicker("val", null);
      $("#filterDownloadDialog #gender").selectpicker("val", null);
      $("#filterDownloadDialog #sponsor").selectpicker("val", null);
      $("#filterDownloadDialog #program").selectpicker("val", null);
      $("#filterDownloadDialog #role").selectpicker("val", null);
    });

    $("#filterDownloadDialog #department").change(function () {
      let department = $(this).val();
      // console.log('Department: '+department)
      if (department != "") {
        $.post(route("api-eschool-departments-courses"), {
          department,
        }).then(
          function (response) {
            // console.log(response)
            $("#filterDownloadDialog #course").html("");
            response.forEach(function (course, index) {
              $("#filterDownloadDialog #course").append(
                '<option value="' + course.id + '">' + course.name + "</option>"
              );
            });
            $("#filterDownloadDialog #course").selectpicker("refresh");
            $("#filterDownloadDialog #course").selectpicker("val", null);
          },
          function (error) {
            console.log(error.responseText);
          }
        );
        $.post(route("api-eschool-departments-intakes"), {
          department,
        }).then(function (response) {
          $("#filterDownloadDialog #intake").html("");
          response.forEach(function (intake, index) {
            $("#filterDownloadDialog #intake").append(
              '<option value="' + intake.id + '">' + intake.name + "</option>"
            );
          });
          $("#filterDownloadDialog #intake").selectpicker("refresh");
          $("#filterDownloadDialog #intake").selectpicker("val", null);
        });
      }
    });

    $("#filterDownloadDialog #course").change(function () {
      let course = $(this).val();
      if (course != "") {
        $.post(route("api-eschool-courses-intakes"), {
          course,
        }).then(
          function (response) {
            // console.log(response)
            $("#filterDownloadDialog #intake").html("");
            response.forEach(function (intake, index) {
              $("#filterDownloadDialog #intake").append(
                '<option value="' + intake.id + '">' + intake.name + "</option>"
              );
            });
            $("#filterDownloadDialog #intake").selectpicker("refresh");
            $("#filterDownloadDialog #intake").selectpicker("val", null);
          },
          function (error) {
            console.log(error.responseText);
          }
        );
      }
    });

    $("#filterResetButton").click(function () {
      $("#filterDownloadDialog #department").selectpicker("val", null);
      $("#filterDownloadDialog #course").selectpicker("val", null);
      $("#filterDownloadDialog #intake").selectpicker("val", null);
      $("#filterDownloadDialog #gender").selectpicker("val", null);
      $("#filterDownloadDialog #sponsor").selectpicker("val", null);
      $("#filterDownloadDialog #program").selectpicker("val", null);
      $("#filterDownloadDialog #role").selectpicker("val", null);
      $("#filterDownloadDialog #before_after").selectpicker("val", null);
      $("#filterDownloadDialog #age").val(null);
      $("#filterDownloadDialog #date_of_birth").val(null);
    });

    $("#filterDownloadButton").click(function () {
      $("#filterDownloadDialog").modal("show");
    });

    $("#uploadStudentsButton").click(function () {
      $("#uploadStudentsDialog").modal("show");
    });
  },
  methods: {
    ...mapActions({
      fetchStudents: "students/fetchStudents",
      setFiltered: "students/setFiltered",
    }),
    route: (name, params = []) => {
      return window.route(name, params);
    },
    setStudents: function (data) {
      this.filtered = this.students = this.getStudents();
    },
    editStudentButton: function (id) {
      axios
        .post(route("api-eschool-students-student"), {
          id,
        })
        .then(
          (response) => {
            let url = route("admin-eschool-students-edit-post");
            $("#studentDetailsForm").attr("action", url);

            let student = response.data;

            $("#studentDialog #studentId").val(student.id);
            $("#studentDialog #surname").val(student.surname);
            $("#studentDialog #first_name").val(student.first_name);
            $("#studentDialog #middle_name").val(student.middle_name);
            $("#studentDialog #phone").val(student.phone);
            $("#studentDialog #email").val(student.email);
            $("#studentDialog #box_no").val(student.box_no);
            $("#studentDialog #post_code").val(student.post_code);
            $("#studentDialog #town").val(student.town);
            $("#studentDialog #date_of_birth").val(student.date_of_birth);
            $("#studentDialog #birth_certificate_no").val(
              student.birth_cert_no
            );
            $("#studentDialog #idno").val(student.idno);
            $("#studentDialog #gender").selectpicker("val", student.gender);
            $("#studentDialog #date_of_admission").val(
              student.date_of_admission
            );
            $("#studentDialog #intake").selectpicker("val", student.intake_id);
            $("#studentDialog #program").selectpicker(
              "val",
              student.program_id
            );
            $("#studentDialog #sponsor").selectpicker(
              "val",
              student.sponsor_id
            );
            $("#studentDialog #student_role").selectpicker(
              "val",
              student.student_role_id
            );

            $("#studentDialog").modal("show");
          },
          function (error) {
            console.log(error);
          }
        );
    },
    uploadPhotoButton: function (id) {
      // console.log(id)
      $("#uploadPhotoDialog").modal("show");
      $("#uploadPhotoId").val(id);
    },
    leaveOutButton: function (student) {
      $("#leaveOutDialog #name").val(student.name);
      $("#leaveOutDialog #name").focus();
      $("#leaveOutDialog").modal("show");
    },
    filterStudents: function () {
      let filtered = (function (arr, query) {
        return arr.filter(function (el) {
          let item = "";

          Object.keys(el).forEach((key) => {
            item += el[key];
          });

          return item.toLowerCase().indexOf(query.toLowerCase()) !== -1;
        });
      })(this.students, this.search);

      this.setFiltered(filtered);
    },
    setPages() {
      this.pages = [];
      let numberOfPages = Math.ceil(this.filtered.length / this.perPage);
      for (let index = 1; index <= numberOfPages; index++) {
        this.pages.push(index);
      }
    },
    paginate(students) {
      if (students.length) {
        let page = this.page;
        let perPage = this.perPage;
        let from = page * perPage - perPage;
        let to = page * perPage;
        return students.slice(from, to);
      } else {
        return [];
      }
    },
    nav: function (p) {
      let items =
        p < 3 ? 5 : p <= this.pages.length ? p + 2 : this.pages.length;
      let start =
        p - 3 < 1
          ? 0
          : p > this.pages.length - 3
          ? this.pages.length - 5
          : p - 3;
      return this.pages.slice(start, items);
    },
  },
  watch: {
    filtered() {
      this.setPages();
    },
  },
  computed: {
    ...mapGetters({
      students: "students/getStudents",
      filtered: "students/getFiltered",
      student: "students/getStudent",
    }),
    displayedStudents() {
      return this.paginate(this.filtered);
    },
  },
};
</script>
