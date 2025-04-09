<template>
    <div>
      <h1>Department Management</h1>
  
      <!-- ปุ่มเพิ่มข้อมูล -->
      <button class="btn btn-primary" @click="showAddPopup = true">Add Department</button>
  
      <!-- Popup สำหรับเพิ่มข้อมูล -->
      <div v-if="showAddPopup" class="popup-overlay">
        <div class="popup">
          <h2>Add Department</h2>
          <form @submit.prevent="addDepartment">
            <input type="text" v-model="newDepartment.dept_id" placeholder="Department ID" required /><br>
            <input type="text" v-model="newDepartment.dept_name" placeholder="Department Name" required /><br>
            <button type="submit">Add</button>
            <button type="button" @click="showAddPopup = false">Cancel</button>
          </form>
        </div>
      </div>
  
      <!-- ตารางแสดงข้อมูล -->
      <table border="1">
        <thead>
          <tr>
            <th>Department ID</th>
            <th>Department Name</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="department in departments" :key="department.dept_id">
            <td>{{ department.dept_id }}</td>
            <td>{{ department.dept_name }}</td>
            <td>
              <button class="btn btn-warning m-1" @click="openEditPopup(department)">Edit</button>
              <button class="btn btn-danger" @click="confirmDelete(department.dept_id)">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
  
      <!-- Popup สำหรับแก้ไขข้อมูล -->
      <div v-if="showEditPopup" class="popup-overlay">
        <div class="popup">
          <h2>Edit Department</h2>
          <form @submit.prevent="updateDepartment">
            <input type="text" v-model="editedDepartment.dept_name" placeholder="Department Name" required /><br>
            <button type="submit">Update</button>
            <button type="button" @click="showEditPopup = false">Cancel</button>
          </form>
        </div>
      </div>
  
      <!-- Popup ยืนยันการลบ -->
      <div v-if="showDeletePopup" class="popup-overlay">
        <div class="popup">
          <h2>Confirm Delete</h2>
          <p>Are you sure you want to delete this Department?</p>
          <button @click="deleteDepartment(deletedeptId)">Yes</button>
          <button @click="showDeletePopup = false">Cancel</button>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import axios from "axios";
  
  export default {
    data() {
      return {
        departments: [],
        newDepartment: {
          dept_id: "",
          dept_name: "",
        },
        editedDepartment: {},
        showAddPopup: false,
        showEditPopup: false,
        showDeletePopup: false,
        deletedeptId: null,
      };
    },
    methods: {
      fetchDepartment() {
        axios.get("http://localhost/php-vue/department_api.php").then((response) => {
          if (response.data.success) {
            this.departments = response.data.data;
          }
        });
      },
      addDepartment() {
        axios.post("http://localhost/php-vue/department_api.php", this.newDepartment).then((response) => {
          if (response.data.success) {
            alert(response.data.message);
            this.fetchDepartment();
            this.newDepartment = {
              dept_id: "",
              dept_name: "",
            };
            this.showAddPopup = false;
          }
        });
      },
      openEditPopup(department) {
        this.editedDepartment = { ...department };
        this.showEditPopup = true;
      },
      updateDepartment() {
        axios.put("http://localhost/php-vue/department_api.php", this.editedDepartment).then((response) => {
          if (response.data.success) {
            alert(response.data.message);
            this.fetchDepartment();
            this.showEditPopup = false;
          }
        });
      },
      confirmDelete(dept_id) {
        this.deletedeptId = dept_id;
        this.showDeletePopup = true;
      },
      deleteDepartment(dept_id) {
        axios.delete("http://localhost/php-vue/department_api.php", {
          data: { dept_id },
        }).then((response) => {
          if (response.data.success) {
            alert(response.data.message);
            this.fetchDepartment();
            this.showDeletePopup = false;
          }
        });
      },
    },
    mounted() {
      this.fetchDepartment();
    },
  };
  </script>
  
  <style>
  .popup-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
  }
  .popup {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    width: 300px;
    text-align: center;
  }
  table {
    width: 100%;
    border-collapse: collapse;
  }
  th, td {
    padding: 8px;
    text-align: left;
    border: 1px solid #ddd;
  }
  </style>
  