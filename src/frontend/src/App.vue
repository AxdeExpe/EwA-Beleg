<script setup lang="ts">
import { RouterLink, RouterView } from 'vue-router'
</script>

<template>
    <header>
      <nav>
        <div class="brand-container">
          <div class="brand">Buchshop</div>
        </div>
        <ul class="nav-container">
          <li><RouterLink to="/" class="nav-link">Home</RouterLink></li>
          <li><RouterLink to="/katalog" class="nav-link">Katalog</RouterLink></li>
          <li><RouterLink to="/about" class="nav-link">About</RouterLink></li>
        </ul>
        <ul class="button-container">
           <li class="punkt-entfernen"><button class="login-button"><RouterLink to="/Login"> Login</RouterLink></button></li>  
        </ul>
      </nav>
    </header>
    <RouterView />
</template>

<style scoped>
header {
  background-color: black;
  color: white;
} 
nav {
  display: flex;
  justify-content: space-between;
  align-items: center; 
  border: 2px solid red; 
  padding: 5px;         
}
.brand {
  font-size: 1.5em;
  margin-right: 30px;
  border: 2px solid red;
  padding: 0.1em;
}
.nav-container {
  display: flex;
  list-style: none;
  flex-grow: 1;
  justify-content: flex;
  margin: 0;
  padding: 0;
}
.nav-link {
  margin-right: 15px;
  text-decoration: none;
  color: white;
  font-size: 20px;
}
.button-container{
  display: flex;
  justify-content: flex-end;
  margin-right: 15px;
}
.punkt-entfernen{
  list-style: none;
}
.login-button{
  text-decoration: none;
  color:black;
}

</style>
