import {combineReducers} from "redux";

export function setLoading(loading: boolean) {
    return {
        type: 'set',
        loading: loading,
    }
}

function loading(state = false, action: any) {
    switch (action.type) {
        case 'set':
            return action.loading;
        default:
            return state;
    }
};

export default combineReducers({
    loading: loading
});
