export async function fetchAllCards() {
    const response = await fetch('/api/card/all');
    if (!response.ok) throw new Error('Failed to fetch cards');
    const result = await response.json();
    return result;
}

export async function fetchCard(uuid) {
    const response = await fetch(`/api/card/${uuid}`);
    if (response.status === 404) return null;
    if (!response.ok) throw new Error('Failed to fetch card');
    const card = await response.json();
    card.text = card.text.replaceAll('\\n', '\n');
    return card;
}

export async function searchCards(query, signal) {
    const response = await fetch(`/api/card/search?query=${query}`);
    if (response.status === 404) return [];
    if (!response.ok) throw new Error('Failed to search cards');
    if (signal.aborted) throw new Error('Search aborted');
    return await response.json();
}

export async function filterBySetCode(route,params) {
    const buildParams = new URLSearchParams(params);
    const response = await fetch(`/api${route}?${buildParams.toString()}`);
    if (!response.ok) throw new Error('Failed to filter cards');
    return await response.json();
}
