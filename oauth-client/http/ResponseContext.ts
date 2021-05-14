class ResponseContext {
    private static res = null;

    static init = (res) => {
        ResponseContext.res = res;
    }

    static getResponse = () => {
        return ResponseContext.res;
    }
}

export default ResponseContext;
