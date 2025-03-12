import { API_URL } from "@/constants/Config";
import { getValueFor } from "@/security/secureStorage";

class ProductService {

    async getProducts(id: number = -1) {
        let target = '';
        if (id === -1) {
            target = 'all';
        } else if (typeof id === "number" && id > 0) {
            target = id.toString();
        }

        const token = await getValueFor("jwt");
        if (typeof token !== "string") {
            return {
                status: "KO",
                data: "Authentication token not found",
            };
        }
        try {
            const response = await fetch(`${API_URL}/products/${target}`, {
                method: "GET",
                headers: {
                    "Content-Type": "application/json",
                    Authorization: `Bearer ${token}`,
                },
            });
            if (!response.ok) {
                const errorData = await response.json();
                return {
                    status: "KO",
                    data: errorData.message || "An error occurred",
                };
            }
            const data = await response.json();
            return {
                status: "OK",
                data: data,
            };
        } catch (error) {
            console.error("Fetch error:", error);
            return {
                status: "KO",
                data: (error instanceof Error ? error.message : "Network error occurred"),
            };
        }
    };

    async getProductSuggestions(id: number) {
        try {
            const response = await fetch(`${API_URL}/products/${id}/suggestions`, {
                method: "GET",
                headers: {
                    "Content-Type": "application/json",
                },
            });

            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            const data = await response.json();
            const suggestions = typeof data === "string" ? JSON.parse(data) : data;
            return {
                status: "OK",
                data: suggestions, // The suggestions array
            };
        } catch (error) {
            console.error("Error fetching product suggestions:", error);
            return {
                status: "ERROR",
                error: error instanceof Error ? error.message : "An unknown error occurred",
            };
        }
    };

}

export default new ProductService();