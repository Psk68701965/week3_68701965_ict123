<template>
  <div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h2 class="mb-0">รายชื่อผู้ติดต่อ</h2>
      <a href="/Add_contact" class="btn btn-primary">Add+</a>
    </div>

    <table class="table table-bordered table-striped align-middle">
      <thead class="table-dark">
        <tr>
          <th>รหัสผู้ติดต่อ</th>
          <th>ชื่อ-นามสกุล</th>
          <th>หัวข้อที่ติดต่อ</th>
          <th>รายละเอียด</th>
          <th>อีเมล</th>
          <th>จัดการ</th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="(item) in contacts" :key="item.contact_id">
          <td>{{ item.contact_id }}</td>
          <td>{{ item.fullname }}</td>
          <td>{{ item.subject }}</td>
          <td>{{ item.detail }}</td>
          <td>{{ item.email }}</td>
          <td>
            <button class="btn btn-danger btn-sm" @click="deleteContact(item.contact_id)">
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
  name: "ContactList",

  setup() {
    const contacts = ref([]);
    const loading = ref(true);
    const error = ref(null);

    const fetchdata = async () => {
      try {
        const response = await fetch("http://localhost/week3_68701965_ict123/php_api/show_contact.php");

        if (!response.ok) {
          throw new Error("ไม่สามารถดึงข้อมูลได้");
        }

        const result = await response.json();
        contacts.value = Array.isArray(result) ? result : (result.data || []);
      } catch (err) {
        error.value = err.message;
        contacts.value = [];
      } finally {
        loading.value = false;
      }
    };

    const deleteContact = async (contactId) => {
      if (!confirm("คุณต้องการลบข้อมูลนี้ใช่หรือไม่?")) return;

      try {
        const response = await fetch("http://localhost/week3_68701965_ict123/php_api/Delete_contact.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json"
          },
          body: JSON.stringify({ contact_id: contactId })
        });

        const result = await response.json();

        if (!response.ok || !result.success) {
          throw new Error(result.message || "ลบข้อมูลไม่สำเร็จ");
        }

        contacts.value = contacts.value.filter((item) => item.contact_id !== contactId);
        alert(result.message || "ลบข้อมูลเรียบร้อย");
      } catch (err) {
        error.value = err.message;
      }
    };

    onMounted(() => {
      fetchdata();
    });

    return {
      contacts,
      loading,
      error,
      deleteContact
    };
  }
};
</script>

<style scoped>
.container {
  max-width: 1200px;
}

table td,
th {
  vertical-align: middle;
}
</style>