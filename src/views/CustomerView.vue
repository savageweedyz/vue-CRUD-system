<template>
  <div>
    <h1>Customer Management</h1>

    <!-- ปุ่มเพิ่มข้อมูล -->
    <button class="btn btn-primary" @click="showAddPopup = true">Add Customer</button>

    <!-- Popup สำหรับเพิ่มข้อมูล -->
    <div v-if="showAddPopup" class="popup-overlay">
      <div class="popup">
        <h2>Add Customer</h2>
        <form @submit.prevent="addCustomer">
          <input type="text" v-model="newCustomer.FirstName" placeholder="First Name" required /><br>
          <input type="text" v-model="newCustomer.LastName" placeholder="Last Name" required /><br>
          <input type="text" v-model="newCustomer.PhoneNumber" placeholder="Phone Number" required /><br>
          <input type="text" v-model="newCustomer.Username" placeholder="Username" required /><br>
          <input type="password" v-model="newCustomer.Password" placeholder="Password" required /><br><br>
          <button type="submit">Add</button>
          <button type="button" @click="showAddPopup = false">Cancel</button>
        </form>
      </div>
    </div>

    <!-- ตารางแสดงข้อมูล -->
    <table border="1">
      <thead>
        <tr>
          <th>Customer ID</th>
          <th>First Name</th>
          <th>Last Name</th>
          <th>Phone Number</th>
          <th>Username</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="customer in customers" :key="customer.CustomerID">
          <td>{{ customer.CustomerID }}</td>
          <td>{{ customer.FirstName }}</td>
          <td>{{ customer.LastName }}</td>
          <td>{{ customer.PhoneNumber }}</td>
          <td>{{ customer.Username }}</td>
          <td>
            <button class="btn btn-warning m-1" @click="openEditPopup(customer)">Edit</button>
            <button class="btn btn-danger" @click="confirmDelete(customer.CustomerID)">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Popup สำหรับแก้ไขข้อมูล -->
    <div v-if="showEditPopup" class="popup-overlay">
      <div class="popup">
        <h2>Edit Customer</h2>
        <form @submit.prevent="updateCustomer">
          <input type="text" v-model="editedCustomer.FirstName" placeholder="First Name" required /><br>
          <input type="text" v-model="editedCustomer.LastName" placeholder="Last Name" required /><br>
          <input type="text" v-model="editedCustomer.PhoneNumber" placeholder="Phone Number" required /><br>
          <input type="text" v-model="editedCustomer.Username" placeholder="Username" required /><br><br>
          <button type="submit">Update</button>
          <button type="button" @click="showEditPopup = false">Cancel</button>
        </form>
      </div>
    </div>

    <!-- Popup ยืนยันการลบ -->
    <div v-if="showDeletePopup" class="popup-overlay">
      <div class="popup">
        <h2>Confirm Delete</h2>
        <p>Are you sure you want to delete this customer?</p>
        <button @click="deleteCustomer(deleteCustomerId)">Yes</button>
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
      customers: [],
      newCustomer: {
        FirstName: "",
        LastName: "",
        PhoneNumber: "",
        Username: "",
        Password: "",
      },
      editedCustomer: {},
      showAddPopup: false,
      showEditPopup: false,
      showDeletePopup: false,
      deleteCustomerId: null,
    };
  },
  methods: {
    fetchCustomers() {
      axios.get("http://localhost/php-vue/customer_api.php").then((response) => {
        if (response.data.success) {
          this.customers = response.data.data;
        }
      });
    },
    addCustomer() {
      axios.post("http://localhost/php-vue/customer_api.php", this.newCustomer).then((response) => {
        if (response.data.success) {
          alert(response.data.message);
          this.fetchCustomers();
          this.newCustomer = {
            FirstName: "",
            LastName: "",
            PhoneNumber: "",
            Username: "",
            Password: "",
          };
          this.showAddPopup = false;
        }
      });
    },
    openEditPopup(customer) {
      this.editedCustomer = { ...customer };
      this.showEditPopup = true;
    },
    updateCustomer() {
      axios.put("http://localhost/php-vue/customer_api.php", this.editedCustomer).then((response) => {
        if (response.data.success) {
          alert(response.data.message);
          this.fetchCustomers();
          this.showEditPopup = false;
        }
      });
    },
    confirmDelete(CustomerID) {
      this.deleteCustomerId = CustomerID;
      this.showDeletePopup = true;
    },
    deleteCustomer(CustomerID) {
      axios.delete("http://localhost/php-vue/customer_api.php", {
        data: { CustomerID },
      }).then((response) => {
        if (response.data.success) {
          alert(response.data.message);
          this.fetchCustomers();
          this.showDeletePopup = false;
        }
      });
    },
  },
  mounted() {
    this.fetchCustomers();
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
