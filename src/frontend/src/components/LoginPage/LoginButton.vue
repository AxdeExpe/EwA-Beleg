<script setup lang="ts">
    import { ref } from "vue";
    import { useRouter } from "vue-router";
    import { updateIsloggedIn, updateIsAdmin, updatePassword, updateUsername  } from "@/store";
    
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
            updateIsloggedIn(true);
            updatePassword(password.value);
            updateUsername(username.value);

            console.log(password.value);
            console.log(username.value);
            
            is_admin.value = true;
            router.push('/admin');
            console.log('Admin-login erfolgreich');
            alert('Admin-login erfolgreich');
          }
          else{
            updateIsAdmin(false);
            updateIsloggedIn(true);
            router.push('/katalog');
            console.log('Login erfolgreich');
            alert('Login erfolgreich');
          }
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
      <div class="flex-box">
        <div class="login">
          <div class="login-header">Sign in</div>
            <input v-model="username" type="text" class="username form-control" placeholder="Username" required><br>
            <input v-model="password" @keydown.enter="doLogin" type="password" class="password form-control" placeholder="Password" required><br>
            <button @click="doLogin" class="btn btn-primary logbtn">Login</button>
            <router-link to="/register" class="reg">
              <div>No Account?</div>
              <div>Register now!</div>
            </router-link>
        </div>
      </div>
</template>
  
<style>
.flex-box {
  height: 100%;
}
.login{
  display: inline-block;
  box-sizing: border-box;
  padding: 20px;
  max-width: 90%;
  min-height: 20%;
  min-width: 300px;
  width: 20%;
  border-radius: 5px;
}
.login-header{
  font-size: calc(1.5vw + 1.5vh + 1vmin);
  font-weight: bold;
  padding-bottom: 20px;
  text-align: center;
  color: black;
}
input[type="text"], input[type="password"] {
  box-sizing: border-box;
  padding: 10px;
  margin: 0px 0px 10px;
  font-weight: bold;
  font-size: calc(.7em + 1vh);
  text-align: center;
  line-height: 20px;
  width: 200px;
  max-width: 100%;
  width: 3000px;
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
.register {
  display: flex;
  flex-direction: column;
  text-align: center;
  font-size: 120%;
  margin: auto;
  color: blue;
  text-decoration: underline;
  cursor: pointer;  
}
.logbtn{
  margin: auto;
  display: flex;
  flex-direction: column;
  width: 100%;
  margin-top: 40px;
  margin-bottom: 15px;
  text-align: center;
  justify-content: center;
  align-items: center;
  height: 50px;
  font-size: 20px;
  font-weight: bold;
  background-color: rgb(33, 150, 243);
  color: white;
  cursor: pointer;
  border-radius: 5px;
  transition: 0.3s;
}
.logbtn:hover{
  background-color: rgb(14, 127, 219);
  font-weight: bold;
  color: white;
}
.reg{
  display: flex;
  flex-direction: column;
  text-align: center;
  justify-content: center;
  align-items: center;
  margin: auto;
  font-size: calc(.5em + 1vw);
  font-weight: bold;
  color: rgb(14, 127, 219);
  border: solid 1px rgb(180, 180, 180);
  border-radius: 5px;
  transition: 0.3s;
}
.reg:hover{
  background-color: rgba(180, 180, 180, 0.8);
  font-weight: bold;
}
</style>