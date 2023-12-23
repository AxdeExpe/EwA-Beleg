<template>
    <div class="flex">
      <div class="flex-box">
        <div class="login">
          <div class="login-header">Register</div>
            <input v-model="username" type="text" class="username form-control" placeholder="Username" required><br>
            <input v-model="password" @keydown.enter="register" type="password" class="password form-control" placeholder="Password" required><br>
            <button @click="register" class="btn btn-primary">Register</button>
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
</style>