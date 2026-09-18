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
            <router-link class="nav-link" to="/customer">Customer</router-link>
          </li>
           <li class="nav-item">
            <router-link class="nav-link" to="/employee">Employee</router-link>
          </li>
           <li class="nav-item">
            <router-link class="nav-link" to="/about">About</router-link>
          </li>
           <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            contact
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="/contacts">แสดงข้อมูลติดต่อ</a></li>
            <li><a class="dropdown-item" href="/Add_contact">เพิ่มข้อมูลติดต่อ</a></li>
          </ul>
        </li>
        </ul>
      </div>
    </div>
  </nav>
</template>

<script>
export default {
  name: "Navbar"
}
</script>


