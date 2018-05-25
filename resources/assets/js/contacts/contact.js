const app_contact = new Vue({
    el: '#app-contact',
    data: {
        companySelected: [],
        contactSelected: [],
        groupSelected: [],
        dataCompany: typeof(dataCompany) !== "undefined" ? dataCompany : [],
        dataContact: typeof(dataContact) !== "undefined" ? dataContact : [],
        dataGroup: typeof(dataGroup) !== "undefined" ? dataGroup : [],
        page: page
    },
    methods: {
        //company
        checkedSelectAllCompany(){
            this.selectAllCompany = !this.selectAllCompany;
        },
        searchCompany(){
            this.$refs['company-search'].submit()
        },
        showModalConfirmDeleteCompany(){
            if(this.companySelected.length > 0){
                $(this.$refs.modal).modal('show');
            }
        },
        deleteCompany(){
            this.$refs['company-delete'].submit();
        },

        //group
        searchGroup(){
            this.$refs['group-search'].submit()
        },
        checkedSelectAllGroup(){
            this.selectAllGroup = !this.selectAllGroup;
        },
        deleteGroup(){
            this.$refs['group-delete'].submit();
        },
        showModalConfirmDeleteGroup(){
            if(this.groupSelected.length > 0){
                $(this.$refs.modal).modal('show');
            }
        },
        //contact
        searchContact(){
            this.$refs['contact-search'].submit()
        },
        checkedSelectAllContact(){
            this.selectAllContact = !this.selectAllContact;
        },
        showModalConfirmDeleteContact(){
            if(this.contactSelected.length > 0){
                $(this.$refs.modal).modal('show');
            }
        },
        deleteContact(){
            this.$refs['contact-delete'].submit();
        }

    },
    computed: {
        selectAllContact: {
            get: function () {
                return this.dataContact ? this.contactSelected.length == this.dataContact.length : false;
            },
            set: function (value) {
                let selected = [];
                if (value) {
                    this.dataContact.forEach(function (data, index) {
                        selected.push(data.id);
                    });
                }
                this.contactSelected = selected;
            }
        },
        selectAllCompany: {
            get: function () {
                return this.dataCompany ? this.companySelected.length == this.dataCompany.length : false;
            },
            set: function (value) {
                let selected = [];
                if (value) {
                    this.dataCompany.forEach(function (data, index) {
                        selected.push(data.id);
                    });
                }
                this.companySelected = selected;
            }
        },
        selectAllGroup: {
            get: function () {
                return this.dataGroup ? this.groupSelected.length == this.dataGroup.length : false;
            },
            set: function (value) {
                let selected = [];
                if (value) {
                    this.dataGroup.forEach(function (data, index) {
                        selected.push(data.id);
                    });
                }
                this.groupSelected = selected;
            }
        }
    }

});
