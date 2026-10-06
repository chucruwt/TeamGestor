const V = {
    email: (e) => /^\S+@\S+\.\S+$/.test(e),
    cpf(c) {
        c = c.replace(/\D/g, '');
        if (c.length !== 11 || /^(\d)\1+$/.test(c)) return false;
        for (let t = 9; t < 11; t++) {
            let s = 0;
            for (let i = 0; i < t; i++) s += c[i] * (t + 1 - i);
            if (((s * 10) % 11) % 10 != c[t]) return false;
        }
        return true;
    },
    mascaraCpf(i) {
        i.addEventListener('input', () => {
            i.value = i.value
                .replace(/\D/g, '')
                .slice(0, 11)
                .replace(/(\d{3})(\d)/, '$1.$2')
                .replace(/(\d{3})(\d)/, '$1.$2')
                .replace(/(\d{3})(\d{1,2})$/, '$1-$2');
        });
    },
};
