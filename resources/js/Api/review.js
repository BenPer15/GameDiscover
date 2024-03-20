export const createReview = async (data) => {
    return await axios.post("/api/review", data);
}

export const updateReview = async (id, data) => {
    return await axios.patch(`/api/review/${id}`, data);
}