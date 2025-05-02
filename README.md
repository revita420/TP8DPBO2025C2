# Janji
Saya Syahraini Revita Puri dengan NIM 2301895 berjanji mengerjakan TP8 DPBO dengan keberkahan-Nya, maka saya tidak akan melakukan kecurangan sesuai yang telah di spesifikasikan, Aamiin

# Desain Program


**1. Database:**

**a. Students Table:**: 
- Primary key : `id(int)`
- Fields : `name`, `nim`, `phone`, `join_date`, `semester`, `email`

**b. Courses Table:**: 
- Primary key : `id(int)`
- Fields : `course_code`, `course_name`, `credits`, `description`

**c. Enrollments Table:**: 
- Primary key : `id(int)`
- Foreign keys : `student_id`, `course_id`
- Fields : `enrollment_date`, `grade`


**2. Relationship:**
- One student can enroll in many courses
- One course can have many students enrolled
- Enrollment berfungsi sebagai penghubung relationship many-to-many antara students dan courses


**3. Arsitektur Project:**

**a. StudentManagament/config:** Berisi paramater koneksi database

**b. StudentManagement/controllers:** Menangani logika untuk mengelola aliran data antara model dan view
- `StudentController.php` : Memproses semua operasi student (mahasiswa)
- `CourseController.php` : Menangani operasi manajemen course (mata kuliah)
- `EnrollmentController.php` : Mengelola operasi pendaftaran mata kuliah

**c. StudentManagement/models:** Berinteraksi dengan database dan menangani manipulasi data
- `DB.class.php` : Koneksi database, mengeksekusi query,dll
- `Student.class.php` : Model student, Menangani operasi data mahasiswa
- `Course.class.php` : Model course, Menangani operasi data mata kuliah
- `Enrollment.class.php` : Model enrollment, menganani operasi data pendaftaran mata kuliah
- `Template.class.php` : Mesin template, membantu merender view

**d. StudentManagement/views:** Bertanggung jawab untuk mengatur bagaimana data ditampilkan kepada user
- `Student.view.php` : Penanganan tampilan student
- `Course.view.php` : Penanganan tampilan course
- `Enrollment.view.php` : Penanganan tampilan enrollment

**e. StudentManagement/templates:** HTML yang digunakan oleh view untuk menampilkan data
- `base.html` : Template dasar
- `student/create,edit,details,list` : Untuk operasi student
- `course/create,edit,details,list` : Untuk operasi course
- `enrollment/create,edit,details,list` : Untuk operasi enrollment

**f. StudentManagement(file root):**
- `index.php` : Titik masuk utama
- `student.php` : Router untuk operasi student
- `course.php` : Router untuk operasi course
- `enrollment.php` : Router untuk operasi enrollment

# Alur Program


**1. View ke Controller**
- User berinteraksi dengan view(misalnya halmaman daftar student)
- View mengirimkan permintaan data ke Controller
- Contoh: Ketika user mengklik tombol "view all student", request dikirim ke StudentController

**2. Controller ke Model**
- Controller menerima permintaan dari View
- Controller kemudian meneruskan permintaan data yang lebih spesifik ke model
- Contoh : StudentController meminta data mahasiswa tertentu dari Student.class.php

**3. Model ke Controller**
- Model mengolah permintaan dan mengambil data dari database
- Model mengembalikan data yang sudah diolah ke controller
- Contoh : Student.class.php mengembalikan informasi mahasiswa ke StudentController

**4. Controller ke View**
- Controller memformat dan mempersiapkan data untuk ditampilkan
- Controller mengirimkan data yang sudah diatur ke view
- View menampilkan infromasi kepada user dalam format yang sesuai
- Contoh : StudentController mengirim data mahasiswa ke Student.view.pho yang kemudian merender template `list.html`


🎥 [Lihat demo video](dokumentasi/StudentManagement.mp4)
