import { get } from '../helpers/api'

export default function (Vue) {

    Vue.user = {

        data: '',
        ready: false,


        init(_this, callback) {

            let component = this;

            get(_this, '/api/user', function (response) {

                component.data = response.data;
                component.ready = true;
                _this.$root.user = data;
                _this.$root.userReady = true;

                callback();

            }, function () {

            });

        },

        getName() {

            return this.data ? this.data.first_name + " " + this.data.last_name + " " + this.data.patronymic : '';

        },


        getRoleDescription() {

            return this.data ? this.data.role.name : '';

        },

        isAdmin() {

            return this.data && this.data.role.name === 'administrator'

        },
        isDoctor() {

            return this.data && this.data.role.name === 'doctor'

        },
        isPatient() {

            return this.data && this.data.role.name === 'patient'

        },

        getPatientData(){

        },

        getId() {
            return this.data.id;
        },

        getPhotoThumb() {
            return this.data.photo ? this.data.photo : 'http://placehold.it/36x36';
        }


    };

    Object.defineProperties(Vue.prototype, {
        $user: {
            get() {
                return Vue.user;
            }
        }
    })

}