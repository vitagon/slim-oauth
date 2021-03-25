import types from './types';

const initialState = {
    message: '',
    user: null
}

const Reducer = (state = initialState, { type, payload }) => {
    switch (type) {
        case types.SET_USER:
            return {
                ...state,
                user: payload
            }
        case 'SET_MESSAGE':
            return {
                ...state,
                message: payload
            }
    }

    return state;
}

export default Reducer;
