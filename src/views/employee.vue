<template>
  <div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h2 class="mb-0">รายชื่อพนักงาน</h2>
      <a href="/Add_employee" class="btn btn-primary">Add+</a>
    </div>

    <table class="table table-bordered table-striped">
      <thead class="table-dark">
        <tr>
          <th>ลำดับที่</th>
          <th>รหัสพนักงาน</th>
          <th>ชื่อ</th>
          <th>นามสกุล</th>
          <th>เบอร์โทร</th>
          <th>ชื่อผู้ใช้</th>
          <th>จัดการ</th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="(item,index) in employees" :key="item.emp_id">
          <td>{{ index + 1 }}</td>
          <td>{{ item.emp_id }}</td>
          <td>{{ item.firstName }}</td>
          <td>{{ item.lastName }}</td>
          <td>{{ item.phone }}</td>
          <td>{{ item.username }}</td>
          <td>
            <button class="btn btn-danger btn-sm" @click="deleteEmployee(item.emp_id)">
              Delete
            </button>
          </td>
        </tr>
      </tbody>
    </table>

    <div v-if="loading" class="text-center">
      <p>กำลังโหลดข้อมูล...</p>
    </div>

    <div v-if="error" class="alert alert-danger">
      {{ error }}
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from "vue";

export default {
  name: "EmployeeList",

  setup() {
    const employees = ref([]);
    const loading = ref(true);
    const error = ref(null);

    const fetchdata = async () => {
      try {
        const response = await fetch("http://localhost/week3_68701965_ict123/php_api/show_employee.php");

        if (!response.ok) {
          throw new Error("ไม่สามารถดึงข้อมูลได้");
        }

        employees.value = await response.json();
      } catch (err) {
        error.value = err.message;
      } finally {
        loading.value = false;
      }
    };

    const deleteEmployee = async (empId) => {
      if (!confirm("คุณต้องการลบพนักงานคนนี้ใช่หรือไม่?")) {
        return;
      }

      try {
        const response = await fetch("http://localhost/week3_68701965_ict123/php_api/Delete_employee.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json"
          },
          body: JSON.stringify({ emp_id: empId })
        });

        const result = await response.json();

        if (!response.ok || !result.success) {
          throw new Error(result.message || "ลบข้อมูลไม่สำเร็จ");
        }

        employees.value = employees.value.filter((item) => item.emp_id !== empId);
        alert(result.message || "ลบข้อมูลเรียบร้อย");
      } catch (err) {
        error.value = err.message;
      }
    };

    onMounted(() => {
      fetchdata();
    });

    return {
      employees,
      loading,
      error,
      deleteEmployee
    };
  }
};
</script>

<style lang=""></style>