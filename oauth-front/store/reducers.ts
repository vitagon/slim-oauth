import { combineReducers } from 'redux';
import Reducer from '@/store/auth/reducer';

const reducers = {
    AuthReducer: Reducer
}

export default combineReducers(reducers);
