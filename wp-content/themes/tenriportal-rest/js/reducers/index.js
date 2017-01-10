import { combineReducers } from "redux"

import posts from "./postsReducer"
import user from "./userReducer"

export default combineReducers({
  posts,
  user,
})
