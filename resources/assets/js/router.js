import VueRouter from 'vue-router'

const Dashboard = require('./views/dashboard/Index.vue');
const Control = require('./views/common/Control.vue');
const Users = require('./views/users/List.vue');
const User = require('./views/users/Item.vue');
const PermissionDenied = require('./views/system/PermissionDenied.vue');
const NotFound = require('./views/system/NotFound.vue');

const Login = require('./views/common/Login.vue');
const SelectAccount = require('./views/common/SelectAccount.vue');
const ResetPass = require('./views/common/ResetPass.vue');

const Register = require('./views/common/Register.vue');

const HistoriesList = require('./views/history/List.vue');
const DoctorsList = require('./views/doctors/List.vue');
const HospitalsList = require('./views/hospitals/List.vue');
const Appointments = require('./views/appointments/List.vue');
const AppointmentItem = require('./views/appointments/Item.vue');
const HistoryItem = require('./views/history/Item.vue');
const HospitalItem = require('./views/hospitals/Item.vue');

const DepartmentsList = require('./views/hospitals/departments/List.vue');
const DepartmentsItem = require('./views/hospitals/departments/Item.vue');



export default new VueRouter({
    mode: 'history',
    base: __dirname,

    routes: [

        { name:'login', path: '/login', component: Login, meta: {title: 'Авторизация', forVisitors: true} },

        { name: 'select-account', path: '/select-account', component: SelectAccount, meta: {title: 'Выберите ученика', forAuth: true}},

        { name: 'reset-pass', path: '/reset-pass', component: ResetPass, meta: {title: 'Восстановление пароля'}},

        {name: 'register', path: '/register', component: Register, meta: {title: 'Регистрация', forVisitors: true}},

        { path: '/', component: Dashboard, meta: {title: 'Показатели', forAuth: true} },
        { name: 'dashboard', path: '/dashboard', component: Dashboard, meta: {title: 'Показатели', forAuth: true} },

        { name: 'histories', path: '/histories', component: HistoriesList, meta: {title: 'My Histories', forAuth: true} },
        { name: 'doctors', path: '/doctors', component: DoctorsList, meta: {title: 'Doctors', forAuth: true} },
        { name: 'hospitals', path: '/hospitals', component: HospitalsList, meta: {title: 'Hospitals', forAuth: true} },
        { name: 'hospital', path: '/hospitals/:id', component: HospitalItem, meta: {title: 'Hospital', forAuth: true} },
        {name: 'hospital-departments', path: '/hospitals/:id/departments', component: DepartmentsList, meta: {title: 'Departments', forAuth: true}},
        {name: 'department', path: '/departments/:id', component: DepartmentsItem, meta: {title: 'Department', forAuth: true}},

        { name: 'control', path: '/control', component: Control, meta: {title: 'Control', forAuth: true} },
         { name: 'users', path: '/control/users', component: Users, meta: {title: 'Users', forAuth: true} },
        { path: '/control/user/:id', name: 'user', component: User, props:true, meta: {title: 'User', forAuth: true} },

        { path: '/appointments', name: 'appointments', component: Appointments, props:true, meta: {title: 'Appointments', forAuth: true} },
        { path: '/appointments/:id', name: 'appointment', component: AppointmentItem, props:true, meta: {title: 'Appointment', forAuth: true} },
        { path: '/histories/:id', name: 'history', component: HistoryItem, props:true, meta: {title: 'History', forAuth: true} },


        { path: '/401', component: PermissionDenied},
        { path: '*', component: NotFound},


    ]

});
