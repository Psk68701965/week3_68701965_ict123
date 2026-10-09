<template>
 <div>
  
<Navbar />

  <!--แสดงหน้าเพจ-->
  <router-view/>

  <!--แสดงfooter-->
  <Footer />

  </div>


</template> 


<script>
import Navbar from './components/Navbar.vue';
import Footer from './components/Footer.vue';
// ✅ Import Bootstrap
import "bootstrap/dist/css/bootstrap.min.css";
import * as bootstrap from "bootstrap"; // ⭐ import ทั้งโมดูล
window.bootstrap = bootstrap; // ✅ ผูกเข้ากับ global window object
export default {
name: "App",
 components: { Navbar, Footer}


};

</script>


