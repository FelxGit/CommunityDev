import Vue from 'vue'
import axios from 'axios'

const state = Vue.observable({
  _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
  user: null,
  isLoggedIn: false,
  loading: false,
  lang: null,
  alert: {
    showAlert: false,
    type: 'info',
    removeIcon: false,
    disableAnimation: false,
    dismissible: true,
    timer: 10,
    message: ''
  },
  loader: {
    color: {
      bLink: '#016EB5', // $brand-link
      btPrimary: 'rgb(24, 25, 26)'
    },
    size: '24px',
    type: [
      'by-task', // 0
      'by-page' // 1
    ]
  },
  trumbowyg: {
    upload_type: {
      image : 'image'
    },
    default: {
        btnsDef: {
            image: {
                dropdown: ['insertImage', 'upload'],
                ico: 'insertImage'
            }
        },
        btns: [
            ['viewHTML'],
            ['undo', 'redo'],
            ['formatting'],
            ['strong', 'em', 'del'],
            ['foreColor', 'backColor', 'fontsize', 'fontfamily', 'highlight'],
            ['emoji'],
            ['superscript', 'subscript'],
            ['link'],
            ['image'],
            ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
            ['unorderedList', 'orderedList'],
            ['horizontalRule'],
            ['removeformat'],
            ['fullscreen']
        ],
        plugins: {
          // Add imagur parameters to upload plugin for demo purposes
          upload: {
              headers: {
                  'Authorization': 'Bearer ' + (localStorage.getItem('chronoknowledge.jwt') ? JSON.parse(localStorage.getItem('chronoknowledge.jwt')) : '')
              }
          }
      }
    },
    uploadDataToken: () => {
      return {name: '_token', value: state._token};
    }
  },
  select: {
    height: '9.375' //px
  }
});

export const getters = {
    loading: () => state.loading,
    loaderColor: () => state.loaderColor,
    alert: () => state.alert,
    lang: () => state.lang,
    _: () => _,
    loader: () => state.loader,
    user: () => state.user,
    isLoggedIn: () => state.isLoggedIn,
    CKEditor: () => state.CKEditor,
    trumbowyg: () => state.trumbowyg
}

export const mutations = {
    setUser: (data) => state.user = data,
    setIsLoggedIn: (val) => state.isLoggedIn = val,
    setLoading: (val) => state.loading = val,
    setLang: (lang) => state.lang = lang,
    setAlert: (data) => {
      if(_.get(data, 'showAlert')) state.alert.showAlert = data.showAlert
      if(_.get(data, 'type')) state.alert.type = data.type
      if(_.get(data, 'removeIcon')) state.alert.removeIcon = data.removeIcon
      if(_.get(data, 'disableAnimation')) state.alert.disableAnimation = data.disableAnimation
      if(_.get(data, 'dismissible')) state.alert.dismissible = data.dismissible
      if(_.get(data, 'timer')) state.alert.timer = data.timer
      if(_.get(data, 'message')) state.alert.message = data.message
    },
    closeAlert : () => {
        mutations.resetAlert()
    },
    resetAlert: () => {
      state.alert = {
        showAlert: false,
        type: 'info',
        removeIcon: false,
        disableAnimation: false,
        dismissible: true,
        timer: 10,
        message: ''
      }
    }
}

export const actions = {
  //
}
