import {createSlice} from '@reduxjs/toolkit'

// Define a type for the slice state
interface LoadingState {
    value: boolean
}

// Define the initial state using that type
const initialState: LoadingState = {
    value: false,
}

export const loadingSlice = createSlice({
    name: 'loading',
    initialState,
    reducers: {
        show: (state) => {
            state.value = true
        },
        hide: (state) => {
            state.value = false
        }
    },
})

export const {show, hide} = loadingSlice.actions

// Other code such as selectors can use the imported `RootState` type
export const showLoading = (state: any) => {
    state.loading.value = true;
}
// Other code such as selectors can use the imported `RootState` type
export const hideLoading = (state: any) => {
    state.loading.value = false;
}

export default loadingSlice.reducer
