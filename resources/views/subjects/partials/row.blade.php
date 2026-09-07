<tr class="subject-row">
    <td>
        @if (!empty($row['subject_id']))
            <input type="hidden" name="rows[{{ $index }}][subject_id]" value="{{ $row['subject_id'] }}">
            <span>{{ $row['subject_id'] }}</span>
        @else
            <span class="erp-muted">-</span>
        @endif
    </td>
    <td>
        <input
            type="number"
            name="rows[{{ $index }}][sl_no]"
            class="erp-input erp-input-sm"
            value="{{ $row['sl_no'] ?? '' }}"
            min="1"
            required
            {{ !empty($readOnly) ? 'readonly' : '' }}
        >
    </td>
    <td>
        <input
            type="text"
            name="rows[{{ $index }}][subject_code]"
            class="erp-input erp-input-sm subject-code-input"
            value="{{ $row['subject_code'] ?? '' }}"
            maxlength="2"
            minlength="2"
            pattern="[A-Za-z0-9]{2}"
            title="Exactly 2 characters"
            required
            {{ !empty($readOnly) ? 'readonly' : '' }}
        >
    </td>
    <td>
        <input
            type="text"
            name="rows[{{ $index }}][subject_name]"
            class="erp-input"
            value="{{ $row['subject_name'] ?? '' }}"
            maxlength="100"
            required
            {{ !empty($readOnly) ? 'readonly' : '' }}
        >
    </td>
    @unless(!empty($readOnly))
        <td>
            <button type="button" class="erp-btn erp-btn-remove remove-subject-row">Remove</button>
        </td>
    @endunless
</tr>
