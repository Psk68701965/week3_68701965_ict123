<template>
  <div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h2 class="mb-0">รายชื่อลูกค้า</h2>
      <a href="/Add_customer" class="btn btn-primary">Add+</a>
    </div>

    <table class="table table-bordered table-striped">
      <thead class="table-dark">
        <tr>
          <th>ลำดับที่</th>
          <th>รหัสลูกค้า</th>
          <th>ชื่อ</th>
          <th>นามสกุล</th>
          <th>เบอร์โทร</th>
          <th>ชื่อผู้ใช้</th>
          <th>จัดการ</th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="(item,index) in customers" :key="item.customer_id">
          <td>{{ index + 1 }}</td>
          <td>{{ item.customer_id }}</td>
          <td>{{ item.firstName }}</td>
          <td>{{ item.lastName }}</td>
          <td>{{ item.phone }}</td>
          <td>{{ item.username }}</td>
          <td>
            <button class="btn btn-danger btn-sm" @click="deleteCustomer(item.customer_id)">
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
  name: "CustomerList",

  setup() {
    const customers = ref([]);
    const loading = ref(true);
    const error = ref(null);

    const fetchdata = async () => {
      try {
        const response = await fetch("http://localhost/week3_68701965_ict123/php_api/show_customer.php");

        if (!response.ok) {
          throw new Error("ไม่สามารถดึงข้อมูลได้");
        }

        const result = await response.json();
        customers.value = Array.isArray(result) ? result : (result.data || []);
      } catch (err) {
        error.value = err.message;
        customers.value = [];
      } finally {
        loading.value = false;
      }
    };

    const deleteCustomer = async (customerId) => {
      if (!confirm("คุณต้องการลบลูกค้าคนนี้ใช่หรือไม่?")) {
        return;
      }

      try {
        const response = await fetch("http://localhost/week3_68701965_ict123/php_api/Delete_customer.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json"
          },
          body: JSON.stringify({ customer_id: customerId })
        });

        const result = await response.json();

        if (!response.ok || !result.success) {
          throw new Error(result.message || "ลบข้อมูลไม่สำเร็จ");
        }

        customers.value = customers.value.filter((item) => item.customer_id !== customerId);
        alert(result.message || "ลบข้อมูลเรียบร้อย");
      } catch (err) {
        error.value = err.message;
      }
    };

    onMounted(() => {
      fetchdata();
    });

    return {
      customers,
      loading,
      error,
      deleteCustomer
    };
  }
};
</script>

<style lang=""></style>