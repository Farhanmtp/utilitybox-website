import {configureStore} from '@reduxjs/toolkit'
import loadingReducer from "@/store/reducer/loading";

const store = configureStore({
    reducer: {
        loading: loadingReducer
    },
})

// Infer the `RootState` and `AppDispatch` types from the store itself
export type RootState = ReturnType<typeof store.getState>
// Inferred type: {posts: PostsState, comments: CommentsState, users: UsersState}
export type AppDispatch = typeof store.dispatch

export default store;
