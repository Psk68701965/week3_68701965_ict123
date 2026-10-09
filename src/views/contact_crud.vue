<template>
  <div class="container mt-4">
    <h2 class="mb-3">รายชื่อผู้ติดต่อ</h2>

    <div class="mb-3">
      <button class="btn btn-primary" @click="openAddModal">
        Add <i class="bi bi-plus-circle"></i>
      </button>
    </div>

    <table class="table table-bordered table-striped">
      <thead class="table-primary">
        <tr>
          <th>รหัสผู้ติดต่อ</th>
          <th>ชื่อ-นามสกุล</th>
          <th>หัวข้อ</th>
          <th>รายละเอียด</th>
          <th>อีเมล</th>
          <th>แก้ไข/ลบ</th>
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
            <button class="btn btn-warning btn-sm" @click="openEditModal(item)">
              แก้ไข
            </button>
            |
            <button class="btn btn-danger btn-sm" @click="deleteContact(item.contact_id)">
              ลบ
            </button>
          </td>
        </tr>
      </tbody>
    </table>

    <div v-if="!loading && contacts.length === 0" class="alert alert-info">
      ไม่มีข้อมูลผู้ติดต่อ
    </div>

    <div v-if="loading" class="text-center"><p>กำลังโหลดข้อมูล...</p></div>
    <div v-if="error" class="alert alert-danger">{{ error }}</div>

    <div class="modal fade" id="editModal" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">{{ isEditMode ? "แก้ไขข้อมูลผู้ติดต่อ" : "เพิ่มผู้ติดต่อใหม่" }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="saveContact">
              <div class="mb-3">
                <label class="form-label">ชื่อ-นามสกุล</label>
                <input v-model="editContact.fullname" type="text" class="form-control" required>
              </div>
              <div class="mb-3">
                <label class="form-label">หัวข้อ</label>
                <input v-model="editContact.subject" type="text" class="form-control" required>
              </div>
              <div class="mb-3">
                <label class="form-label">รายละเอียด</label>
                <textarea v-model="editContact.detail" class="form-control" required></textarea>
              </div>
              <div class="mb-3">
                <label class="form-label">อีเมล</label>
                <input v-model="editContact.email" type="email" class="form-control" required>
              </div>
              <button type="submit" class="btn btn-success">
                {{ isEditMode ? "บันทึกการแก้ไข" : "เพิ่มผู้ติดต่อ" }}
              </button>
            </form>
          </div>
        </div>
      </div>
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
    const editContact = ref({});
    const isEditMode = ref(false);
    let editModal = null;

    const fetchContacts = async () => {
      try {
        const response = await fetch("http://localhost/week3_68701965_ict123/php_api/contact_crud.php?action=read");

        if (!response.ok) {
          throw new Error(`โหลดข้อมูลผู้ติดต่อไม่สำเร็จ (HTTP ${response.status})`);
        }

        const result = await response.json();

        if (result.success) {
          contacts.value = result.data;
        } else {
          error.value = result.message;
        }
      } catch (err) {
        error.value = err.message;
      } finally {
        loading.value = false;
      }
    };

    onMounted(() => {
      fetchContacts();
      const modalEl = document.getElementById("editModal");
      editModal = new window.bootstrap.Modal(modalEl);
    });

    // ✅ เปิด Modal เพิ่มผู้ติดต่อใหม่
    const openAddModal = () => {
      isEditMode.value = false;
      editContact.value = {
        fullname: "",
        subject: "",
        phone: "",
        email: ""
      };
      editModal.show();
    };

    // ✅ เปิด Modal แก้ไขผู้ติดต่อ
    const openEditModal = (contact) => {
      isEditMode.value = true;
      editContact.value = { ...contact };
      editModal.show();
    };

    // ✅ ใช้ฟังก์ชันเดียวสำหรับทั้งเพิ่ม/แก้ไข
    const saveContact = async () => {
      const url = "http://localhost/week3_68701965_ict123/php_api/contact_crud.php";
      const method = isEditMode.value ? "PUT" : "POST";

      try {
        const response = await fetch(url, {
          method,
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(editContact.value)
        });

        const result = await response.json();

        if (result.success) {
          alert(result.message);
          fetchContacts();
          editModal.hide();
        } else {
          alert(result.message);
        }
      } catch (err) {
        alert("เกิดข้อผิดพลาด: " + err.message);
      }
    };

    // ✅ ลบผู้ติดต่อ
    const deleteContact = async (id) => {
      if (!confirm("คุณต้องการลบข้อมูลนี้ใช่หรือไม่?")) return;
      try {
        const response = await fetch("http://localhost/week3_68701965_ict123/php_api/contact_crud.php", {
          method: "DELETE",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ contact_id: id })
        });

        if (!response.ok) {
          throw new Error(`ลบข้อมูลผู้ติดต่อไม่สำเร็จ (HTTP ${response.status})`);
        }

        const result = await response.json();
        if (result.success) {
          contacts.value = contacts.value.filter(c => c.contact_id !== id);
          alert(result.message);
        } else {
          alert(result.message);
        }
      } catch (err) {
        alert("เกิดข้อผิดพลาด: " + err.message);
      }
    };

    return {
      contacts,
      loading,
      error,
      editContact,
      isEditMode,
      openAddModal,
      openEditModal,
      saveContact,
      deleteContact
    };
  }
};
</script>
