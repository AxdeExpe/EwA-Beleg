<script setup lang="ts">
    import { ref } from "vue";
    import { useRouter } from "vue-router";
    import { updateIsloggedIn, updateIsAdmin  } from "@/store";
    
    let username = ref('');
    let password = ref('');
    let is_admin = ref(false);
    let router = useRouter();
    
    let doLogin = async () => {
      try {
        let response = await fetch('http://ivm108.informatik.htw-dresden.de/ewa/g08/backend/login.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: new URLSearchParams({
            username: username.value,
            password: password.value,
          }),
        });

        if (response.ok) {
          let data = await response.json();

          if(data.is_admin === '1'){
            updateIsAdmin(true);
          }
          else{
            updateIsAdmin(false);
          }

          console.log('Login erfolgreich');
          updateIsloggedIn(true);
          router.push('/');
          alert('Login erfolgreich');
        } else if (response.status === 400) {
          console.error('username or password are empty or not set, no POST-Request');
          alert('username or password are empty or not set, no POST-Request');
          username.value = '';
          password.value = '';
        } else if (response.status === 401) {
          console.error('not authorized');
          alert('not authorized');
          username.value = '';
          password.value = '';
        } else if (response.status === 500) {
          console.error('Server Error');
          alert('Server Error');
          username.value = '';
          password.value = '';
        }
      } catch (error) {
        console.error('Fehler bei der Anfrage:', error);}
    };
</script>

<template>
    <div class="flex">
      <div class="flex-box">
        <div class="login">
          <div class="login-header">Sign in</div>
            <input v-model="username" type="text" class="username form-control" placeholder="Username" required><br>
            <input v-model="password" @keydown.enter="doLogin" type="password" class="password form-control" placeholder="Password" required><br>
            <button @click="doLogin" class="btn btn-primary">Login</button>
            <button @click="router.push('/register')" class="btn btn-primary">Register</button>
        </div>
      </div>
    </div>
</template>
  
<style>
html{
  height: 100%;
  margin: 0;
  padding: 0;
  width: 100%;
}
.flex {
  display: flex;
  flex-direction: column;
  /* background-color: black; */
  height: 100%;
}
.flex-box {
  /* background-color: black; */
  display: flex;
  align-items: center;
  justify-content: center;
  flex: 1;
  flex-direction: column;
}
.login{
  background-color: white;
  padding: 20px;
  display: inline-block;
  max-width: 90%;
  box-sizing: border-box;
}
.login-header{
  font-size: 300%;
  font-weight: bold;
  padding-bottom: 20px;
  text-align: center;
  color: black;
}
input[type="text"], input[type="password"] {
  padding: 10px;
  line-height: 20px;
  margin: 0px 0px 10px;
  font-weight: bold;
  width: 200px;
  text-align: center;
  max-width: 100%;
  width: 3000px;
  box-sizing: border-box;
}
input[type="submit"] {
  background-color: white;
  padding: 50px;
  box-sizing: border-box;
  max-width: 100%;
  width: 3000px;
  font-weight: bold;
}
input[type="submit"]:hover {
  background-color: #440000;
  font-weight: bold;
  color: white;
}

.register{
  
  left: 100%;
  position: relative;

  background-color: rgb(212, 212, 212);
  padding: 10px;
  margin: auto;
  box-sizing: border-box;
  max-width: 100%;
  width: fit-content;
  font-weight: bold;
  text-align: center;

  border: 1px solid black;
  border-radius: 7px;
  cursor: pointer;
}

.register:hover {
  background-color: rgba(212, 212, 212,0.8);
  font-weight: bold;
}
</style>