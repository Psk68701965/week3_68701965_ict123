<template>
  <div class="container mt-4">
    <!-- หัวข้อหน้า -->
    <h2 class="mb-3">รายชื่อผู้ติดต่อ</h2>
    <div>
    <a href="/Add_contact" class="btn btn-primary mb-3">Add+</a>
  </div>
    <!-- ตารางแสดงข้อมูล -->
    <table class="table table-bordered table-striped">
      <thead class="table-dark">
        <tr>      
          <th>รหัสผู้ติดต่อ</th> 
          <th>ชื่อ-นามสกุล</th>   
          <th>หัวข้อที่ติดต่อ</th>           
          <th>รายละเอียด</th>        
          <th>อีเมล</th>     
        </tr>
      </thead>

      <tbody>
        <!-- วนลูปข้อมูล contacts -->
        <tr v-for="(item,index) in contacts" :key="item.contact_id">  
          <td>{{ item.contact_id }}</td> 
          <td>{{ item.fullname }}</td>   
          <td>{{ item.subject }}</td>    
          <td>{{ item.detail }}</td>    
          <td>{{ item.email }}</td>    
        </tr>
      </tbody>
    </table>

    <!-- Loading: แสดงระหว่างรอข้อมูล -->
    <div v-if="loading" class="text-center">
      <p>กำลังโหลดข้อมูล...</p>
    </div>

    <!-- Error: แสดงเมื่อเกิดข้อผิดพลาด -->
    <div v-if="error" class="alert alert-danger">
      {{ error }}
    </div>
  </div>
</template>

<script>
// import ฟังก์ชันจาก Vue (Composition API)
import { ref, onMounted } from "vue";

export default {
  name: "ContactList", // ชื่อ component

  setup() {
    // -----------------------------
    // state (ตัวแปร reactive)
    // -----------------------------
    const contacts = ref([]); // เก็บข้อมูลผู้ติดต่อ (array)
    const loading = ref(true); // สถานะโหลดข้อมูล
    const error = ref(null);   // เก็บ error

    // -----------------------------
    // ฟังก์ชันดึงข้อมูลจาก API
    // -----------------------------
    const fetchdata = async () => {
      try {
        // เรียก API (PHP)
        const response = await fetch("http://localhost/week3_68701965_ict123/php_api/show_contact.php");

        // ตรวจสอบว่าการเรียกสำเร็จหรือไม่
        if (!response.ok) {
          throw new Error("ไม่สามารถดึงข้อมูลได้");
        }

        // แปลง response เป็น JSON
        contacts.value = await response.json();

      } catch (err) {
        // ถ้า error ให้เก็บข้อความไว้แสดง
        error.value = err.message;

      } finally {
        // ไม่ว่าจะสำเร็จหรือ error ให้หยุด loading
        loading.value = false;
      }
    };

    // -----------------------------
    // lifecycle: ทำงานเมื่อ component โหลดเสร็จ
    // -----------------------------
    onMounted(() => {
      fetchdata(); // เรียก API ทันที
    });

    // -----------------------------
    // return ค่าไปใช้ใน template
    // -----------------------------
    return {
      contacts,
      loading,
      error
    };
  }
};
</script>
<style lang="">
    
</style>