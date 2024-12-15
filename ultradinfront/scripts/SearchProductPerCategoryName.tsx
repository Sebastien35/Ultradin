import { API_URL } from "@/constants/Config";
import { getValueFor } from "@/security/secureStorage";

export const SearchProductPerCategoryNames = async (terms: Array<string>) => {
    // Build the query string
    const query = terms.map(term => `names[]=${encodeURIComponent(term)}`).join('&');
    const searchUrl = `API_URL?${query}`;

    try {
        const response = await fetch(searchUrl, {
            method: "GET",
            headers: {
                "Content-Type": "application/json",
            },
        });

        if (!response.ok) {
            throw new Error(`Error: ${response.statusText}`);
        }

        const data = await response.json();
        return {
            status: "OK",
            data: data,
        };
    } catch (error) {
        console.error("SearchProducts error:", error);
        return {
            status: "KO",
            data: (error as Error).message,
        };
    }
};