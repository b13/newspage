<label for="select-category">Category</label>
<f:form.select id="select-category" name="filter[category]"
               options="{options.items}" optionLabelField="name" optionValueField="uid"
               prependOptionLabel="Bitte wählen" value="{filter.category}"/>
