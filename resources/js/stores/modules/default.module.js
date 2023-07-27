// import api from "~/config/libs/axios";

const state = {
    data : {}
};

const getters = {
    data: (state) => state.data,
};

const mutations = {
    set: (state, payload) => {
        state.data = { ...state.data, ...payload };
    },
};

const actions = {
//    
};

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions,
};
