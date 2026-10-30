<template>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <router-link class="navbar-brand fw-bold" to="/">68701965</router-link>

      <button
        class="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navbarNav"
      >
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <router-link class="nav-link" to="/">Home</router-link>
          </li>
          <li class="nav-item">
            <router-link class="nav-link" to="/about">About</router-link>
          </li>

          <li class="nav-item dropdown">
            <button
              class="nav-link dropdown-toggle btn btn-link text-white text-decoration-none"
              type="button"
              @click="toggleMenu('customer')"
            >
              Customer
            </button>
            <ul class="dropdown-menu" :class="{ show: openMenu === 'customer' }">
              <li><router-link class="dropdown-item" to="/customer" @click="closeMenu">customer</router-link></li>
              <li><router-link class="dropdown-item" to="/customer_crud" @click="closeMenu">customer_crud</router-link></li>
            </ul>
          </li>

          <li class="nav-item dropdown">
            <button
              class="nav-link dropdown-toggle btn btn-link text-white text-decoration-none"
              type="button"
              @click="toggleMenu('employee')"
            >
              Employee
            </button>
            <ul class="dropdown-menu" :class="{ show: openMenu === 'employee' }">
              <li><router-link class="dropdown-item" to="/employee" @click="closeMenu">employee</router-link></li>
              <li><router-link class="dropdown-item" to="/employee_crud" @click="closeMenu">employee_crud</router-link></li>
            </ul>
          </li>

          <li class="nav-item dropdown">
            <button
              class="nav-link dropdown-toggle btn btn-link text-white text-decoration-none"
              type="button"
              @click="toggleMenu('contact')"
            >
              contact
            </button>
            <ul class="dropdown-menu" :class="{ show: openMenu === 'contact' }">
              <li><router-link class="dropdown-item" to="/contacts" @click="closeMenu">แสดงข้อมูลติดต่อ</router-link></li>
              <li><router-link class="dropdown-item" to="/Add_contact" @click="closeMenu">เพิ่มข้อมูลติดต่อ</router-link></li>
              <li><router-link class="dropdown-item" to="/contact_crud" @click="closeMenu">แก้ไขข้อมูลติดต่อ</router-link></li>
            </ul>
          </li>
          <li class="nav-item">
            <router-link class="nav-link" to="/product">product</router-link>
          </li>
          <li class="nav-item">
            <router-link class="nav-link" to="/asset">asset</router-link>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>

<script>
import { ref } from 'vue'

export default {
  name: 'Navbar',
  setup() {
    const openMenu = ref('')

    const toggleMenu = (menuName) => {
      openMenu.value = openMenu.value === menuName ? '' : menuName
    }

    const closeMenu = () => {
      openMenu.value = ''
    }

    return {
      openMenu,
      toggleMenu,
      closeMenu
    }
  }
}
</script>

<style scoped>
.nav-item.dropdown {
  position: relative;
}

.dropdown-menu {
  display: none;
  position: absolute;
  top: 100%;
  left: 0;
  z-index: 1000;
  min-width: 12rem;
  padding: 0.5rem 0;
  margin: 0.125rem 0 0;
  background-color: #fff;
  border: 1px solid rgba(0, 0, 0, 0.15);
  border-radius: 0.25rem;
}

.dropdown-menu.show {
  display: block;
}

.btn-link {
  border: none;
  background: transparent;
  padding: 0.5rem 1rem;
}
</style>


